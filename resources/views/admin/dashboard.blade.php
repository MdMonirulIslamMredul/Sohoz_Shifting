@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Products</small>
                <h4>{{ $stats['products'] }}</h4>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Services</small>
                <h4>{{ $stats['services'] }}</h4>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Blogs</small>
                <h4>{{ $stats['blogs'] }}</h4>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Team</small>
                <h4>{{ $stats['team_members'] }}</h4>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('admin.partners.index') }}" class="text-decoration-none text-reset">
                <div class="card p-3"><small>Partners</small>
                    <h4>{{ $stats['partners'] ?? 0 }}</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Gallery</small>
                <h4>{{ $stats['galleries'] }}</h4>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card p-3"><small>Contacts</small>
                <h4>{{ $stats['contacts'] }}</h4>
            </div>
        </div>
    </div>
    <div class="card p-4">
        <h5>Recent Contacts</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentContacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                    </tr>@empty<tr>
                            <td colspan="3">No contact submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
