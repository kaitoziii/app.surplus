<form method="post" action="{{ route('profile.update') }}">
@csrf
@method('patch')

<div>
    <x-input-label value="Name" />
    <x-text-input name="name" required />
</div>

<div>
    <x-input-label value="Phone" />
    <x-text-input name="phone" required />
</div>

<div>
    <x-input-label value="Address" />
    <textarea name="address" required></textarea>
</div>

<x-primary-button>Save</x-primary-button>

</form>