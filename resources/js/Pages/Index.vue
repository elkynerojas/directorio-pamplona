<template>
    <AppLayout>
        <Head title="Inicio" />

        <!-- Hero -->
        <section
            class="hero-section"
            style="background: linear-gradient(160deg, #8B0000 0%, #C0252D 55%, #D94040 100%); padding: 60px 0 80px;"
        >
            <v-container>
                <v-row justify="center" class="text-center mb-8">
                    <v-col cols="12" md="8">
                        <div class="d-flex justify-center mb-4">
                            <v-icon icon="mdi-map-marker-multiple" color="secondary" size="56" />
                        </div>
                        <h1 class="text-h3 text-h4-sm font-weight-bold text-white mb-3">
                            Directorio Digital<br>
                            <span style="color: #F9C300;">Pamplona</span>
                        </h1>
                        <p class="text-body-1 text-white opacity-80 mb-6">
                            Encuentra los negocios y servicios de Pamplona, Norte de Santander
                        </p>

                        <!-- Barra de búsqueda -->
                        <v-row justify="center">
                            <v-col cols="12" md="8">
                                <v-text-field
                                    v-model="search"
                                    placeholder="Buscar negocios, servicios..."
                                    prepend-inner-icon="mdi-magnify"
                                    variant="solo"
                                    rounded="pill"
                                    hide-details
                                    clearable
                                    bg-color="white"
                                    @update:modelValue="onSearch"
                                />
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Categorías -->
        <v-container class="mt-n6">
            <v-row justify="center">
                <v-col cols="12">
                    <v-card rounded="xl" elevation="3" class="py-4 px-4">
                        <div class="d-flex flex-wrap justify-center" style="gap: 10px;">
                            <v-chip
                                :variant="!selectedCategory ? 'elevated' : 'tonal'"
                                :color="!selectedCategory ? 'primary' : 'default'"
                                size="large"
                                class="px-5"
                                @click="selectCategory(null)"
                                style="cursor: pointer; font-weight: 500; letter-spacing: 0.01em;"
                            >
                                <v-icon start icon="mdi-view-grid" />
                                Todos
                            </v-chip>

                            <v-chip
                                v-for="cat in categories"
                                :key="cat.id"
                                :variant="selectedCategory === cat.id ? 'elevated' : 'tonal'"
                                :color="selectedCategory === cat.id ? 'primary' : 'default'"
                                size="large"
                                class="px-5"
                                @click="selectCategory(cat)"
                                style="cursor: pointer; font-weight: 500; letter-spacing: 0.01em;"
                            >
                                <v-icon v-if="cat.icon" :icon="cat.icon" start size="16" />
                                {{ cat.name }}
                            </v-chip>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <!-- Grid de negocios -->
        <v-container class="mt-6 mb-8">
            <div class="d-flex align-center justify-space-between mb-4">
                <h2 class="text-h6 font-weight-bold" style="color: #C0252D;">
                    {{ filteredCount }} negocio{{ filteredCount !== 1 ? 's' : '' }} encontrado{{ filteredCount !== 1 ? 's' : '' }}
                </h2>
            </div>

            <!-- Sin resultados -->
            <div v-if="businesses.data.length === 0" class="text-center py-16">
                <v-icon icon="mdi-store-search" size="64" color="grey-lighten-1" />
                <p class="text-h6 text-grey mt-4">No encontramos negocios con esos criterios</p>
                <v-btn color="primary" variant="tonal" class="mt-4" @click="clearFilters">
                    Limpiar filtros
                </v-btn>
            </div>

            <v-row v-else>
                <v-col
                    v-for="(business, index) in businesses.data"
                    :key="business.id"
                    cols="12"
                    sm="6"
                    md="4"
                    lg="3"
                >
                    <BusinessCard
                        :business="business"
                        :visible="visibleCards.includes(business.id)"
                        :style="{ 'animation-delay': `${(index % 12) * 60}ms` }"
                    />
                </v-col>
            </v-row>

            <!-- Paginación -->
            <div v-if="businesses.last_page > 1" class="d-flex justify-center mt-8">
                <v-pagination
                    v-model="currentPage"
                    :length="businesses.last_page"
                    :total-visible="7"
                    color="primary"
                    rounded="circle"
                    @update:modelValue="goToPage"
                />
            </div>
        </v-container>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import AppLayout from '@/Layouts/AppLayout.vue';
import BusinessCard from '@/Components/BusinessCard.vue';

const props = defineProps({
    businesses: Object,
    categories: Array,
    filters: Object,
    current_category: { type: Object, default: null },
});

const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.current_category?.id ?? null);
const currentPage = ref(props.businesses?.current_page || 1);
const visibleCards = ref([]);

const filteredCount = computed(() => props.businesses?.total || 0);

function buildParams() {
    const params = {};
    if (search.value) params.search = search.value;
    return params;
}

function baseUrl() {
    return props.current_category
        ? route('categories.show', props.current_category.slug)
        : route('home');
}

const onSearch = useDebounceFn(() => {
    currentPage.value = 1;
    router.get(baseUrl(), buildParams(), { preserveState: true, replace: true });
}, 350);

function selectCategory(cat) {
    currentPage.value = 1;
    if (cat === null) {
        selectedCategory.value = null;
        router.get(route('home'), buildParams(), { preserveState: true, replace: true });
    } else {
        selectedCategory.value = cat.id;
        router.get(route('categories.show', cat.slug), buildParams(), { preserveState: true, replace: true });
    }
}

function clearFilters() {
    search.value = '';
    selectedCategory.value = null;
    currentPage.value = 1;
    router.get(route('home'), {}, { preserveState: false });
}

function goToPage(page) {
    router.get(baseUrl(), { ...buildParams(), page }, { preserveState: true });
}

onMounted(() => {
    // Activar animación de cards con un pequeño delay
    setTimeout(() => {
        visibleCards.value = props.businesses.data.map(b => b.id);
    }, 100);
});

watch(() => props.businesses, () => {
    visibleCards.value = [];
    setTimeout(() => {
        visibleCards.value = props.businesses.data.map(b => b.id);
    }, 50);
});
</script>
