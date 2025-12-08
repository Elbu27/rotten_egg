<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label>Name</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full"
                   value="{{ old('name', $user->name) }}">
        </div>

        <div>
            <label>Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full"
                   value="{{ old('email', $user->email) }}">
        </div>

        <button class="btn btn-primary mt-3">
            Save
        </button>
    </form>
</section>