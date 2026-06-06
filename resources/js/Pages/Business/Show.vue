<template>
    <AppLayout>
        <Head :title="business.name" />

        <!-- Header con imagen principal -->
        <section class="position-relative" style="height: 320px; overflow: hidden;">
            <v-img
                :src="business.main_image_url || `https://picsum.photos/seed/${business.id + 100}/1200/400`"
                height="320"
                cover
                class="w-100"
            >
                <div
                    class="position-absolute w-100 h-100"
                    style="background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.1) 60%);"
                />
            </v-img>

            <div class="position-absolute" style="bottom: 24px; left: 0; right: 0;">
                <v-container>
                    <v-chip :color="business.category?.color || 'primary'" size="small" class="mb-2" elevation="2">
                        <v-icon v-if="business.category?.icon" :icon="business.category.icon" start size="14" />
                        {{ business.category?.name }}
                    </v-chip>
                    <h1 class="text-h4 font-weight-bold text-white">{{ business.name }}</h1>
                    <div class="d-flex align-center ga-1 mt-1 text-white opacity-80">
                        <v-icon icon="mdi-map-marker" size="16" />
                        <span class="text-body-2">{{ business.address }}</span>
                    </div>
                </v-container>
            </div>

            <!-- Botón volver -->
            <v-btn
                icon
                variant="tonal"
                color="white"
                class="position-absolute"
                style="top: 16px; left: 16px; background: rgba(0,0,0,0.4);"
                @click="$inertia.visit(route('home'))"
            >
                <v-icon icon="mdi-arrow-left" />
            </v-btn>
        </section>

        <v-container class="mt-6">
            <v-row>
                <!-- Columna principal -->
                <v-col cols="12" md="8">

                    <!-- Descripción -->
                    <v-card rounded="xl" elevation="1" class="mb-4">
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            Acerca del negocio
                        </v-card-title>
                        <v-card-text class="px-5 pb-5">
                            <p class="text-body-1" style="line-height: 1.8; white-space: pre-line;">
                                {{ business.long_description || business.short_description }}
                            </p>
                        </v-card-text>
                    </v-card>

                    <!-- Galería de imágenes -->
                    <v-card v-if="allImages.length > 0" rounded="xl" elevation="1" class="mb-4">
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            <v-icon icon="mdi-image-multiple" start color="secondary" />
                            Galería
                        </v-card-title>
                        <v-card-text class="px-4 pb-4">
                            <v-row dense>
                                <v-col
                                    v-for="(img, i) in allImages"
                                    :key="i"
                                    cols="6"
                                    sm="4"
                                >
                                    <v-img
                                        :src="img"
                                        height="150"
                                        cover
                                        rounded="lg"
                                        class="gallery-img"
                                        style="cursor: pointer; transition: opacity 0.2s;"
                                        @click="openLightbox(i)"
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Mapa -->
                    <v-card v-if="business.latitude != null && business.longitude != null" rounded="xl" elevation="1" class="mb-4">
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            <v-icon icon="mdi-map" start color="secondary" />
                            Ubicación
                        </v-card-title>
                        <v-card-text class="px-4 pb-4">
                            <BusinessMap
                                :lat="parseFloat(business.latitude)"
                                :lng="parseFloat(business.longitude)"
                                :name="business.name"
                                height="300px"
                            />
                            <p class="text-caption text-grey mt-2">
                                <v-icon icon="mdi-map-marker" size="14" /> {{ business.address }}
                            </p>
                        </v-card-text>
                    </v-card>

                </v-col>

                <!-- Columna lateral: contacto y horario -->
                <v-col cols="12" md="4">

                    <!-- Botones de contacto -->
                    <v-card rounded="xl" elevation="1" class="mb-4">
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            Contactar
                        </v-card-title>
                        <v-card-text class="px-4 pb-4">
                            <v-btn
                                v-if="business.whatsapp"
                                color="success"
                                block
                                class="mb-3"
                                size="large"
                                prepend-icon="mdi-whatsapp"
                                :href="business.whatsapp_url"
                                target="_blank"
                                elevation="1"
                            >
                                Enviar WhatsApp
                            </v-btn>

                            <v-btn
                                v-if="business.phone"
                                color="primary"
                                variant="tonal"
                                block
                                class="mb-3"
                                size="large"
                                prepend-icon="mdi-phone"
                                :href="`tel:${business.phone}`"
                                elevation="0"
                            >
                                {{ business.phone }}
                            </v-btn>

                            <v-btn
                                v-if="business.email"
                                color="secondary"
                                variant="tonal"
                                block
                                class="mb-3"
                                size="large"
                                prepend-icon="mdi-email"
                                :href="`mailto:${business.email}`"
                                elevation="0"
                            >
                                Enviar correo
                            </v-btn>

                            <v-btn
                                v-if="business.website"
                                color="primary"
                                variant="outlined"
                                block
                                size="large"
                                prepend-icon="mdi-web"
                                :href="business.website"
                                target="_blank"
                                elevation="0"
                            >
                                Visitar sitio web
                            </v-btn>
                        </v-card-text>
                    </v-card>

                    <!-- Redes sociales -->
                    <v-card
                        v-if="business.instagram || business.facebook || business.tiktok || business.youtube"
                        rounded="xl"
                        elevation="1"
                        class="mb-4"
                    >
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            Redes Sociales
                        </v-card-title>
                        <v-card-text class="px-4 pb-4 d-flex flex-wrap gap-2">
                            <v-btn
                                v-if="business.instagram"
                                icon
                                variant="tonal"
                                color="pink-darken-1"
                                :href="`https://instagram.com/${business.instagram}`"
                                target="_blank"
                            >
                                <v-icon icon="mdi-instagram" />
                                <v-tooltip activator="parent">Instagram</v-tooltip>
                            </v-btn>

                            <v-btn
                                v-if="business.facebook"
                                icon
                                variant="tonal"
                                color="blue-darken-3"
                                :href="`https://facebook.com/${business.facebook}`"
                                target="_blank"
                            >
                                <v-icon icon="mdi-facebook" />
                                <v-tooltip activator="parent">Facebook</v-tooltip>
                            </v-btn>

                            <v-btn
                                v-if="business.tiktok"
                                icon
                                variant="tonal"
                                color="black"
                                :href="`https://tiktok.com/@${business.tiktok}`"
                                target="_blank"
                            >
                                <v-icon icon="mdi-music-note" />
                                <v-tooltip activator="parent">TikTok</v-tooltip>
                            </v-btn>

                            <v-btn
                                v-if="business.youtube"
                                icon
                                variant="tonal"
                                color="red-darken-1"
                                :href="`https://youtube.com/${business.youtube}`"
                                target="_blank"
                            >
                                <v-icon icon="mdi-youtube" />
                                <v-tooltip activator="parent">YouTube</v-tooltip>
                            </v-btn>
                        </v-card-text>
                    </v-card>

                    <!-- Horario -->
                    <v-card v-if="business.schedule?.length" rounded="xl" elevation="1" class="mb-4">
                        <v-card-title class="text-h6 font-weight-bold pt-5 px-5" style="color: #C0252D;">
                            <v-icon icon="mdi-clock-outline" start color="secondary" />
                            Horario
                        </v-card-title>
                        <v-card-text class="px-4 pb-4">
                            <div
                                v-for="day in business.schedule"
                                :key="day.day"
                                class="d-flex justify-space-between align-center py-1"
                                style="border-bottom: 1px solid #f0f0f0;"
                            >
                                <span class="text-body-2 font-weight-medium">{{ day.day }}</span>
                                <span class="text-caption text-grey-darken-1">
                                    {{ day.closed ? 'Cerrado' : `${day.open} - ${day.close}` }}
                                </span>
                            </div>
                        </v-card-text>
                    </v-card>

                </v-col>
            </v-row>
        </v-container>

        <!-- Lightbox -->
        <v-dialog v-model="lightbox" max-width="900">
            <v-card rounded="xl" class="position-relative pa-0" style="overflow: hidden;">
                <v-btn
                    icon
                    variant="text"
                    class="position-absolute"
                    style="top: 8px; right: 8px; z-index: 10;"
                    @click="lightbox = false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>

                <v-img :src="allImages[lightboxIndex]" max-height="600" contain class="bg-black" />

                <div class="d-flex justify-center align-center pa-3 ga-4">
                    <v-btn icon variant="tonal" :disabled="lightboxIndex === 0" @click="lightboxIndex--">
                        <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                    <span class="text-caption">{{ lightboxIndex + 1 }} / {{ allImages.length }}</span>
                    <v-btn icon variant="tonal" :disabled="lightboxIndex === allImages.length - 1" @click="lightboxIndex++">
                        <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                </div>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BusinessMap from '@/Components/BusinessMap.vue';

const props = defineProps({
    business: Object,
});

const lightbox = ref(false);
const lightboxIndex = ref(0);

const allImages = computed(() => {
    const imgs = [];
    if (props.business.main_image_url) imgs.push(props.business.main_image_url);
    if (props.business.images?.length) {
        imgs.push(...props.business.images.map(i => i.url));
    }
    if (imgs.length === 0) {
        imgs.push(`https://picsum.photos/seed/${props.business.id + 100}/1200/400`);
    }
    return imgs;
});

function openLightbox(index) {
    lightboxIndex.value = index;
    lightbox.value = true;
}
</script>
