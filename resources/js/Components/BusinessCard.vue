<template>
    <v-card
        class="business-card h-100"
        :class="{ 'card-animate': visible }"
        rounded="xl"
        elevation="2"
        @click="goToDetail"
        style="cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease;"
        @mouseenter="hovered = true"
        @mouseleave="hovered = false"
        :style="hovered ? 'transform: translateY(-4px); box-shadow: 0 8px 30px rgba(192,37,45,0.15) !important;' : ''"
    >
        <div class="position-relative">
            <v-img
                :src="business.main_image_url || `https://picsum.photos/seed/${business.id}/400/220`"
                height="180"
                cover
                class="rounded-t-xl"
            >
                <template #placeholder>
                    <div class="d-flex align-center justify-center fill-height bg-grey-lighten-3">
                        <v-icon icon="mdi-image" color="grey" size="40" />
                    </div>
                </template>
            </v-img>

            <v-chip
                size="small"
                class="position-absolute"
                :style="`top: 10px; left: 10px; font-weight: 600; background: white; color: ${business.category?.color || '#C0252D'}; border: 1px solid ${business.category?.color || '#C0252D'};`"
                elevation="2"
            >
                {{ business.category?.name }}
            </v-chip>

            <v-btn
                icon
                size="small"
                color="success"
                class="position-absolute"
                style="bottom: -18px; right: 14px;"
                elevation="3"
                :href="business.whatsapp_url"
                target="_blank"
                @click.stop
            >
                <v-icon icon="mdi-whatsapp" size="20" />
                <v-tooltip activator="parent" location="top">Enviar WhatsApp</v-tooltip>
            </v-btn>
        </div>

        <v-card-text class="pt-6 pb-2">
            <div class="text-subtitle-1 font-weight-bold text-truncate mb-1" style="color: #C0252D;">
                {{ business.name }}
            </div>

            <div class="d-flex align-center ga-1 mb-2 text-caption text-grey-darken-1">
                <v-icon icon="mdi-map-marker" size="14" color="secondary" />
                <span class="text-truncate">{{ business.address }}</span>
            </div>

            <p class="text-caption text-grey-darken-2" style="
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            ">
                {{ business.short_description }}
            </p>
        </v-card-text>

        <v-card-actions class="pt-0 px-4 pb-3">
            <v-btn
                variant="tonal"
                color="primary"
                size="small"
                append-icon="mdi-arrow-right"
                block
            >
                Ver detalle
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    business: { type: Object, required: true },
    visible: { type: Boolean, default: false },
});

const hovered = ref(false);

function goToDetail() {
    router.visit(route('businesses.show', props.business.slug));
}
</script>
