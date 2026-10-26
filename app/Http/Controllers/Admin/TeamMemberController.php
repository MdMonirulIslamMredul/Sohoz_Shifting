<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        return view('admin.team-members.index', [
            'teamMembers' => TeamMember::orderBy('sort_order')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $team_member)
    {
        return view('admin.team-members.edit', ['teamMember' => $team_member]);
    }

    public function update(Request $request, TeamMember $team_member)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            if ($team_member->photo) {
                Storage::disk('public')->delete($team_member->photo);
            }
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        $team_member->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $team_member)
    {
        if ($team_member->photo) {
            Storage::disk('public')->delete($team_member->photo);
        }

        $team_member->delete();

        return back()->with('success', 'Team member deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
