@extends('admin.layouts.app')
@section('title', 'Edit Team Member')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Team Member</h4>
        <form method="POST" action="{{ route('admin.team-members.update', $teamMember) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.team-members._form')<button class="btn btn-primary mt-3">Update</button></form>
    </div>
@endsection
