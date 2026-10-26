<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::orderBy('order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validatedData($request));

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validatedData($request));

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
