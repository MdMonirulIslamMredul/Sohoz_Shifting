<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class SystemToolController extends Controller
{
    public function index(): View
    {
        $diagnostics = $this->getDiagnostics();

        return view('admin.system-tools.index', compact('diagnostics'));
    }

    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');
            $output = Artisan::output();
            return back()->with('success', 'All caches (config, routes, views, application) cleared successfully!<br><pre class="small text-muted mt-2 mb-0">' . e($output) . '</pre>');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to clear caches: ' . $e->getMessage());
        }
    }

    public function optimize(): RedirectResponse
    {
        try {
            Artisan::call('optimize');
            $output = Artisan::output();
            return back()->with('success', 'Application optimized and cached successfully!<br><pre class="small text-muted mt-2 mb-0">' . e($output) . '</pre>');
        } catch (Throwable $e) {
            return back()->with('error', 'Optimization failed: ' . $e->getMessage());
        }
    }

    public function runMigrations(): RedirectResponse
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            return back()->with('success', 'Database migrations executed successfully!<br><pre class="small text-muted mt-2 mb-0">' . e($output) . '</pre>');
        } catch (Throwable $e) {
            return back()->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    public function linkStorage(): RedirectResponse
    {
        try {
            if (!function_exists('symlink')) {
                return back()->with('warning', 'The <code>symlink()</code> function is disabled on this server. However, our built-in image fallback route is active, so all uploaded files are still served seamlessly without needing a symlink!');
            }

            Artisan::call('storage:link');
            $output = Artisan::output();

            return back()->with('success', 'Storage link command completed!<br><pre class="small text-muted mt-2 mb-0">' . e($output) . '</pre>');
        } catch (Throwable $e) {
            return back()->with('warning', 'Storage link note: ' . $e->getMessage() . '. Note: The built-in image fallback route is active, so uploads will continue to display normally.');
        }
    }

    public function emergencySetup(Request $request)
    {
        $setupKey = env('SETUP_KEY');

        // Security check: require matching key if SETUP_KEY is configured
        if (!empty($setupKey) && $request->query('key') !== $setupKey) {
            abort(403, 'Unauthorized: Invalid or missing setup key. Provide ?key=' . urlencode('your_secret_key') . ' configured in .env');
        }

        // If no setup key configured, only allow if users table has no users yet (brand new install)
        if (empty($setupKey)) {
            $hasUsers = false;
            try {
                if (Schema::hasTable('users')) {
                    $hasUsers = \App\Models\User::exists();
                }
            } catch (Throwable $e) {
                $hasUsers = false;
            }

            if ($hasUsers) {
                abort(403, 'Unauthorized: To run web setup on an existing site, define SETUP_KEY in your .env file.');
            }
        }

        $results = [];

        // 1. Run migrations
        try {
            Artisan::call('migrate', ['--force' => true]);
            $results['migrations'] = ['success' => true, 'output' => Artisan::output()];
        } catch (Throwable $e) {
            $results['migrations'] = ['success' => false, 'output' => $e->getMessage()];
        }

        // 2. Storage link
        try {
            if (function_exists('symlink')) {
                Artisan::call('storage:link');
                $results['storage_link'] = ['success' => true, 'output' => Artisan::output()];
            } else {
                $results['storage_link'] = [
                    'success' => true,
                    'output' => 'symlink() is disabled on this hosting. Automatic Laravel route fallback will serve uploaded images seamlessly.',
                ];
            }
        } catch (Throwable $e) {
            $results['storage_link'] = ['success' => false, 'output' => $e->getMessage()];
        }

        // 3. Clear cache
        try {
            Artisan::call('optimize:clear');
            $results['cache'] = ['success' => true, 'output' => Artisan::output()];
        } catch (Throwable $e) {
            $results['cache'] = ['success' => false, 'output' => $e->getMessage()];
        }

        return response()->view('admin.system-tools.setup-result', compact('results'));
    }

    private function getDiagnostics(): array
    {
        $storagePath = storage_path('app/public');
        $publicStorage = public_path('storage');
        $storageWritable = is_writable(storage_path());
        $bootstrapWritable = is_writable(base_path('bootstrap/cache'));

        $disabledFunctions = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        $symlinkEnabled = function_exists('symlink') && !in_array('symlink', $disabledFunctions, true);

        $storageLinked = file_exists($publicStorage);

        $dbConnected = false;
        $dbError = null;
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $dbConnected = true;
        } catch (Throwable $e) {
            $dbError = $e->getMessage();
        }

        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'storage_writable' => $storageWritable,
            'bootstrap_writable' => $bootstrapWritable,
            'symlink_function_enabled' => $symlinkEnabled,
            'storage_linked' => $storageLinked,
            'storage_disk_root' => config('filesystems.disks.public.root'),
            'image_fallback_active' => true,
            'db_connected' => $dbConnected,
            'db_error' => $dbError,
        ];
    }
}
