@extends('frontend.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3">Welcome, {{ $user->name }}</h1>
                    <p class="mb-1">Email: {{ $user->email }}</p>
                    <p class="text-muted">This is your user dashboard. Customize as needed.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
