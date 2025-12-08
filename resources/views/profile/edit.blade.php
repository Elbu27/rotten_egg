@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Profile</h1>

    <div class="card mb-4">
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
