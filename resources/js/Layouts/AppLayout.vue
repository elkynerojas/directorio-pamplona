<template>
    <v-app :theme="'pamplona'">
        <v-app-bar elevation="0" style="background: #C0252D; border-bottom: 4px solid #F9C300;">
            <v-container class="d-flex align-center">
                <Link href="/" class="text-decoration-none">
                    <div class="d-flex align-center ga-2">
                        <v-icon icon="mdi-map-marker-multiple" color="secondary" size="32" />
                        <div>
                            <div class="text-h6 font-weight-bold text-white">Directorio Pamplona</div>
                            <div class="text-caption opacity-80" style="line-height: 1; color: #F9C300;">Norte de Santander</div>
                        </div>
                    </div>
                </Link>

                <v-spacer />

                <v-btn
                    variant="text"
                    color="white"
                    :href="route('home')"
                    prepend-icon="mdi-home"
                    class="d-none d-sm-flex"
                >
                    Inicio
                </v-btn>
            </v-container>
        </v-app-bar>

        <v-main>
            <slot />
        </v-main>

        <v-footer class="mt-8" style="background: #C0252D; border-top: 4px solid #F9C300;">
            <v-container>
                <v-row align="center">
                    <v-col cols="12" sm="6">
                        <div class="d-flex align-center ga-2">
                            <v-icon icon="mdi-map-marker-multiple" color="secondary" />
                            <span class="text-white font-weight-medium">Directorio Pamplona</span>
                        </div>
                        <p class="text-caption text-white opacity-70 mt-1">
                            El directorio de negocios de Pamplona, Norte de Santander
                        </p>
                    </v-col>
                    <v-col cols="12" sm="6" class="text-sm-right">
                        <p class="text-caption text-white opacity-50">
                            © {{ new Date().getFullYear() }} Directorio Pamplona. Todos los derechos reservados.
                        </p>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
