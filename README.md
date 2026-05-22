# Directorio Comercial Digital — Pamplona, N. de S.

Directorio de negocios local para Pamplona, Norte de Santander, Colombia. Permite a los ciudadanos buscar, filtrar y contactar comercios locales. El panel de administración permite gestionar categorías y negocios.

## Stack

| Capa | Tecnología |
|------|------------|
| Backend | Laravel 13 |
| Panel admin | Filament 4 |
| Frontend | Vue 3 + Inertia.js + Vuetify 3 |
| Build tool | Vite |
| Base de datos | MySQL 8 |
| Infraestructura | Docker + Docker Compose |
| Mapa | Leaflet + OpenStreetMap |
| Imágenes | Almacenamiento local (Laravel storage) |

## Requisitos

- Docker
- Docker Compose

No se necesita instalar PHP, Node ni Composer en la máquina local.

## Setup rápido

```bash
# 1. Clonar el repositorio
git clone <url-del-repo>
cd directorio

# 2. Copiar variables de entorno
cp .env.example .env

# 3. Levantar los contenedores
docker compose up -d

# 4. Instalar dependencias PHP (si no están en vendor/)
docker compose exec laravel-app composer install

# 5. Generar clave de aplicación
docker compose exec laravel-app php artisan key:generate

# 6. Migrar la base de datos y cargar datos de ejemplo
docker compose exec laravel-app php artisan migrate --seed

# 7. Crear enlace simbólico para imágenes
docker compose exec laravel-app php artisan storage:link
```

## Servicios disponibles

| Servicio | URL local | Descripción |
|----------|-----------|-------------|
| Aplicación | http://localhost | Vista pública del directorio |
| Panel admin | http://localhost/admin | Filament (requiere login) |
| PHPMyAdmin | http://localhost:8080 | Gestor de base de datos |
| Mailpit | http://localhost:8025 | Bandeja de correos de desarrollo |
| Vite dev server | http://localhost:5173 | Hot reload del frontend |

## Credenciales de desarrollo

| Campo | Valor |
|-------|-------|
| Email | `admin@directorio-pamplona.co` |
| Contraseña | `password` |

## Comandos frecuentes

```bash
# Artisan (Laravel)
docker compose exec laravel-app php artisan <comando>

# Migraciones
docker compose exec laravel-app php artisan migrate
docker compose exec laravel-app php artisan migrate:fresh --seed

# Frontend (dev con hot reload)
docker compose exec node npm run dev

# Frontend (build para producción)
docker compose exec node npm run build

# Ver logs
docker compose logs -f laravel-app
```

## Estructura del proyecto

```
app/
├── Filament/Resources/
│   ├── CategoryResource.php     # CRUD de categorías
│   └── BusinessResource.php     # CRUD de negocios
├── Http/Controllers/
│   └── BusinessController.php   # Vistas públicas
└── Models/
    ├── Category.php
    ├── Business.php
    └── BusinessImage.php

resources/js/
├── Pages/
│   ├── Index.vue                # Listado con búsqueda y filtros
│   └── Business/Show.vue        # Detalle del negocio
└── Components/
    ├── BusinessCard.vue
    ├── BusinessMap.vue           # Mapa Leaflet
    └── AppLayout.vue

database/
├── migrations/                  # 6 migraciones
└── seeders/
    ├── CategorySeeder.php        # 12 categorías reales de Pamplona
    └── DatabaseSeeder.php        # 100 negocios ficticios con Faker
```

## Base de datos

### `categories`
`name`, `slug`, `description`, `icon` (MDI), `color` (hex), `order`, `is_active`

### `businesses`
`name`, `slug`, `category_id`, `short_description`, `address`, `whatsapp`, `phone`, `email`, `website`, redes sociales, `schedule` (JSON), `latitude`, `longitude`, `main_image`, `is_active`, `is_featured`

### `business_images`
`business_id`, `path`, `alt_text`, `order`

## Tema visual

Basado en la bandera de Pamplona, N. de S.:

- Rojo: `#C0252D`
- Amarillo: `#F9C300`
- Azul (escudo): `#1A237E`
- Fuente: Poppins

## Fases del proyecto

- **Fase 1 (MVP actual):** Solo el administrador gestiona los negocios. Sin registro público.
- **Fase 2 (planeada):** Los dueños de negocios pueden crear cuenta y administrar su propia información.
