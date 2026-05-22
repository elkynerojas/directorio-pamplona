# Directorio Comercial Digital — Pamplona, Norte de Santander

Directorio de negocios local para Pamplona, N. de S., Colombia. MVP funcional con panel admin y vista pública.

## Stack

- **Backend**: Laravel 13 (se instaló la última versión estable)
- **Admin panel**: Filament 4
- **Frontend**: Vue 3 + Inertia.js + Vuetify 3
- **Build tool**: Vite
- **Base de datos**: MySQL 8
- **Entorno**: Docker (docker-compose)
- **Mapa**: Leaflet + OpenStreetMap (sin APIs de terceros)
- **Imágenes**: Almacenamiento local (Laravel storage/public)
- **WhatsApp**: Redirección a `https://wa.me/{número}` — sin proveedores externos

## Entorno Docker

Cuatro servicios definidos en `docker-compose.yml`:

| Servicio | Imagen | Puerto |
|----------|--------|--------|
| `app` | PHP 8.2-fpm custom | interno |
| `nginx` | nginx:alpine | 8080:80 |
| `db` | mysql:8.0 | 3306:3306 |
| `node` | node:22-alpine | 5173:5173 (Vite dev) |

Comandos frecuentes:
```bash
docker compose up -d                          # levantar entorno
docker compose exec app php artisan ...       # comandos artisan
docker compose exec node npm run dev          # Vite dev server
docker compose exec app php artisan migrate --seed  # migrar + seed
```

## Estructura de Base de Datos

### `categories`
- `name` (req), `slug`, `description`, `icon` (MDI), `color` (hex), `order`, `is_active`

### `businesses`
- **Requeridos**: `name`, `slug`, `category_id`, `short_description`, `address`, `whatsapp`
- **Opcionales**: `long_description`, `phone`, `email`, `website`, `instagram`, `facebook`, `tiktok`, `youtube`, `schedule` (JSON), `latitude`, `longitude`, `main_image`, `is_active`, `is_featured`

### `business_images`
- `business_id`, `path`, `alt_text`, `order`

## Panel Admin (Filament)

- Ruta: `/admin`
- **CategoryResource**: CRUD categorías con ícono MDI y color
- **BusinessResource**: CRUD negocios en tabs (Básico / Contacto / Ubicación / Imágenes / Horario)
  - Ubicación: campos lat/lng + mapa Leaflet interactivo para hacer clic y asignar coordenadas
  - Imágenes: upload imagen principal + galería múltiple ordenable

## Vistas Públicas (Inertia + Vue)

| Ruta | Componente | Descripción |
|------|-----------|-------------|
| `/` | `Pages/Index.vue` | Grid de cards + búsqueda + filtro por categoría |
| `/negocios/{slug}` | `Pages/Business/Show.vue` | Detalle: galería, mapa, botones contacto |

## Tema Visual

- Paleta basada en la bandera de Pamplona NS: rojo (`#C0252D`), amarillo (`#F9C300`), blanco. Azul del escudo (`#1A237E`) como accent.
- Fuente: Poppins (Google Fonts)
- Vuetify 3 con tema personalizado — nada genérico
- Animaciones en cards (fade-in al entrar al viewport) y transiciones de página

## Datos de Ejemplo

- 12 categorías reales del comercio de Pamplona
- 100 negocios ficticios generados con Faker (español)
- Coordenadas distribuidas alrededor de Pamplona (~7.3756°N, 72.6493°W)
- Imágenes placeholder con `picsum.photos`

## Fases del Proyecto

- **MVP (Fase 1)**: Solo el admin gestiona negocios. Sin autenticación pública.
- **Fase 2** (futura): Los dueños de negocios administran su propia información.

## Convenciones

- Código en inglés (variables, métodos, rutas), contenido en español
- Slugs auto-generados desde el nombre
- Números de WhatsApp en formato internacional sin `+` ni espacios: `573001234567`
- Imágenes servidas desde `storage/app/public` con enlace simbólico vía `php artisan storage:link`
