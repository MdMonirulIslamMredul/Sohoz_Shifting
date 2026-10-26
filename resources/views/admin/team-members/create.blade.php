@extends('admin.layouts.app')
@section('title', 'Create Team Member')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Team Member</h4>
        <form method="POST" action="{{ route('admin.team-members.store') }}" enctype="multipart/form-data">
            @csrf@include('admin.team-members._form')<button class="btn btn-primary mt-3">Save</button></form>
    </div>
@endsection
