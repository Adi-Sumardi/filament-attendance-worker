<div>
    <div class="container max-w-sm mx-auto">
        <div class="p-6 mt-3 bg-white rounded-lg shadow-lg">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h2 class="mb-2 text-2xl font-bold">Informasi Pegawai</h2>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <p><strong>Nama Pegawai : </strong> {{ Auth::user()->name }}</p>
                        <p><strong>Kantor : </strong>{{ $schedule->office->name }}</p>
                        <p><strong>Shift : </strong>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} -
                            {{ $schedule->shift->end_time }}) wib</p>
                        @if ($schedule->is_wfa)
                            <p class="text-green-500"><strong>Status : </strong>WFA</p>
                        @else
                            <p><strong>Status : </strong>WFO</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="mb-2 font-bold text-l">Waktu Datang</h4>
                            <p><strong>{{ $attendance ? $attendance->start_time : '-' }}</p>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="mb-2 font-bold text-l">Waktu Pulang</h4>
                            <p><strong>{{ $attendance ? $attendance->end_time : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-2 text-2xl font-bold">Presensi</h2>
                    <div id="map" class="mb-4 border border-gray-300 rounded-lg" wire:ignore></div>
                    @if (session()->has('error'))
                        <div style="color: red; padding: 10px; border: 1px solid red; backgroud-color: #fdd;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form class="mt-3 row g-3" wire:submit="store" enctype="multipart/form-data">
                        <button type="button" onclick="tagLocation()"
                            class="px-4 py-2 text-white bg-blue-500 rounded">Tag Location</button>
                        @if ($insideRadius)
                            <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded">Submit
                                Presensi</button>
                        @endif
                    </form>
                </div>

            </div>
        </div>

    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let lat;
        let lng;
        const office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
        const radius = {{ $schedule->office->radius }};
        let component;
        let marker;
        document.addEventListener('livewire:initialized', function() {
            component = @this;
            map = L.map('map').setView([{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}],
                15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const circle = L.circle(office, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        })


        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);

                    if (isWithinRadius(lat, lng, office, radius)) {
                        component.set('insideRadius', true);
                        component.set('latitude', lat);
                        component.set('longitude', lng);
                    }
                })
            } else {
                alert('Tidak bisa get location');
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            const is_wfa = {!! json_encode($schedule->is_wfa) !!}
            if (is_wfa) {
                return true;
            } else {
                let distance = map.distance([lat, lng], center);
                return distance <= radius;
            }

        }
    </script>

</div>
