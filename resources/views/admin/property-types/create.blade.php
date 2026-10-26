@extends('admin.layouts.app')
@section('title', 'Add Property Type')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Add Property Type</h4>
        <a href="{{ route('admin.property-types.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 p-4">
        <form method="POST" action="{{ route('admin.property-types.store') }}">
            @csrf
            @include('admin.property-types._form')
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2-circle me-1"></i> Save Property Type
                </button>
            </div>
        </form>
    </div>
@endsection
