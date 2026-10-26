@extends('admin.layouts.app')
@section('title', 'Team Members')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Team Members</h4><a href="{{ route('admin.team-members.create') }}" class="btn btn-primary">Add Team Member</a>
    </div>
    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($teamMembers as $teamMember)
                    <tr>
                        <td>{{ $teamMember->name }}</td>
                        <td>
                            @if ($teamMember->photo)
                                <img src="{{ asset('storage/' . $teamMember->photo) }}" alt="{{ $teamMember->name }}"
                                    class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            @else
                                <span class="text-muted"><i class="bi bi-person-circle" style="font-size: 2rem;"></i></span>
                                {{-- <span class="text-muted">No image</span> --}}
                            @endif
                        </td>

                        </td>
                        <td>{{ $teamMember->designation }}</td>
                        <td>{{ $teamMember->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-end"><a href="{{ route('admin.team-members.edit', $teamMember) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.team-members.destroy', $teamMember) }}"
                                class="d-inline">@csrf @method('DELETE')<button onclick="return confirm('Delete item?')"
                                    class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                </tr>@empty<tr>
                        <td colspan="4">No team members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>{{ $teamMembers->links() }}
    </div>
@endsection
