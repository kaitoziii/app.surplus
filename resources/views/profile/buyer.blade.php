<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

   
    <!-- <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6"> -->
<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
@csrf
@method('patch')

<!-- ================= FOTO ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- FOTO -->
   <div>
    <label class="block mb-2 font-medium">Foto / Logo Toko</label>

    <img id="preview"
    src="{{ $user->avatar_url ? asset('storage/'.$user->avatar_url) : '' }}"
    class="w-24 h-24 object-cover rounded mb-2">
    <input type="file" name="avatar" onchange="previewImage(event)">
  @if($user->avatar_url)
        <p class="text-sm text-green-600 mt-1">
            File sudah tersimpan ✔
        </p>
    @endif
</div>

</div>


<!-- ================= GRID ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- NAME -->
    <div>
        <x-input-label value="Name *" />
        <x-text-input name="name"
            class="mt-1 block w-full"
            :value="old('name', $user->name)" required />
    </div>

    <!-- EMAIL -->
    <div>
        <x-input-label value="Email *" />
        <x-text-input name="email"
            class="mt-1 block w-full"
            :value="old('email', $user->email)" required />
    </div>

    <!-- LAT -->
    <div>
         <x-input-label value="Latitude *" />
<x-text-input id="latitude" name="latitude"
    class="mt-1 block w-full"
    :value="old('latitude', $user->latitude)" required />

    </div>

    <!-- LNG -->
    <div>
         <x-input-label value="Longitude *" />
       <x-text-input id="longitude" name="longitude"
    class="mt-1 block w-full"
    :value="old('longitude', $user->longitude)" required />
    </div>

</div>


<!-- BUTTON LOKASI -->
<div>
    <button type="button" onclick="getLocation()"
        class="bg-gray-200 px-4 py-2 rounded">
        📍 Ambil Lokasi Saya
    </button>
</div>


<!-- ================= FULL ================= -->


<!-- PHONE -->
<div>
    <x-input-label value="Phone *" />
    <x-text-input name="phone"
        class="mt-1 block w-full"
        :value="old('phone', $user->phone)" required />
</div>

<!-- ALAMAT -->
<div>
    <x-input-label value="Alamat Toko *" />
    <textarea name="store_address"
        class="mt-1 block w-full border rounded"
        rows="2" required>{{ old('store_address', $user->store_address) }}</textarea>
</div>




        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>

        <!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Map -->
<div id="map" style="height: 300px; margin-top: 10px;"></div>

<!-- Input -->
<!-- <input type="text" id="latitude" name="latitude" placeholder="Latitude">
<input type="text" id="longitude" name="longitude" placeholder="Longitude"> -->
@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([-7.447, 112.718], 13); // default Sidoarjo

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([-7.447, 112.718], { draggable: true }).addTo(map);

    // klik map
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);

        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });

    // drag marker
    marker.on('dragend', function(e) {
        var position = marker.getLatLng();

        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // isi input
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            // 🔥 update map
            map.setView([lat, lng], 15);

            // 🔥 update marker
            marker.setLatLng([lat, lng]);

        });
    } else {
        alert("Browser tidak support GPS");
    }
}
function previewProof(event) {
    const reader = new FileReader();

    reader.onload = function () {
        document.getElementById('previewProof').src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}

function previewImage(event) {
    const reader = new FileReader();

    reader.onload = function () {
        document.getElementById('preview').src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}


</script>
@endpush
    </form>
</section>
