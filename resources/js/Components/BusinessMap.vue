<template>
    <div ref="mapEl" :style="{ height: height, width: '100%', borderRadius: '12px' }" />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    lat: { type: Number, required: true },
    lng: { type: Number, required: true },
    name: { type: String, default: '' },
    height: { type: String, default: '350px' },
});

const mapEl = ref(null);
let map = null;

onMounted(async () => {
    const L = (await import('leaflet')).default;
    await import('leaflet/dist/leaflet.css');

    // Fix para los íconos de Leaflet con bundlers
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    map = L.map(mapEl.value).setView([props.lat, props.lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    L.marker([props.lat, props.lng])
        .addTo(map)
        .bindPopup(`<strong>${props.name}</strong>`)
        .openPopup();
});

onUnmounted(() => {
    if (map) map.remove();
});
</script>
