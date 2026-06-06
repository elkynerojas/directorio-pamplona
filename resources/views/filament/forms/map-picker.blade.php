<div
    x-data="{
        lat: $wire.entangle('data.latitude').live,
        lng: $wire.entangle('data.longitude').live,
        map: null,
        marker: null,
        defaultLat: 7.37560000,
        defaultLng: -72.64930000,
        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },
        initMap() {
            const centerLat = parseFloat(this.lat) || this.defaultLat;
            const centerLng = parseFloat(this.lng) || this.defaultLng;

            this.map = L.map(this.$refs.mapContainer).setView([centerLat, centerLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href=\'https://www.openstreetmap.org/copyright\'>OpenStreetMap</a>'
            }).addTo(this.map);

            if (centerLat !== this.defaultLat || centerLng !== this.defaultLng) {
                this.placeMarker(centerLat, centerLng);
            }

            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(8);
                this.lng = e.latlng.lng.toFixed(8);
                this.placeMarker(e.latlng.lat, e.latlng.lng);
            });
        },
        placeMarker(lat, lng) {
            if (this.marker) {
                this.marker.setLatLng([lat, lng]);
            } else {
                this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.lat = pos.lat.toFixed(8);
                    this.lng = pos.lng.toFixed(8);
                });
            }
        }
    }"
    x-init="init()"
    class="col-span-full"
>
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>

    <div class="mb-2 text-sm text-gray-500">
        Haga clic en el mapa para establecer la ubicación, o arrastre el marcador.
    </div>

    <div
        x-ref="mapContainer"
        style="height: 380px; width: 100%; border-radius: 8px; z-index: 1;"
        class="border border-gray-300 dark:border-gray-600"
    ></div>
</div>
