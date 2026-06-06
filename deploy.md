# Despliegue — Directorio Pamplona

## Infraestructura

| Recurso | Valor |
|---------|-------|
| Plataforma | GCP Compute Engine |
| Instancia | `directorio-pamplona` |
| Zona | `southamerica-east1-b` |
| Directorio en servidor | `/opt/directorio` |
| Owner del directorio | `root` (requiere `sudo`) |
| URL pública | Configurada con nginx reverse proxy en el servidor |

## Arquitectura en producción

El servidor corre dos contenedores definidos en `docker-compose.prod.yml`:

| Contenedor | Imagen | Descripción |
|------------|--------|-------------|
| `directorio_app` | `docker/php/Dockerfile` (PHP 8.4-fpm + nginx + supervisor) | Sirve la app Laravel + nginx interno en puerto 80 |
| `directorio_db` | `mysql:8.0.31` | Base de datos persistente en volumen `mysql_data` |

El nginx del servidor (host) actúa como reverse proxy hacia `127.0.0.1:8080`, que es el puerto expuesto por `directorio_app`.

## Flujo de despliegue

El script `deploy.sh` (en la raíz del proyecto) automatiza todo el proceso:

```
git pull origin main
  └─ Trae el código más reciente

docker run node:22-alpine npm ci && npm run build
  └─ Construye assets frontend (Vite + copia vendors: Leaflet, MDI)
  └─ Genera public/build/ y public/vendor/

docker compose -f docker-compose.prod.yml up -d --build
  └─ Reconstruye la imagen del app (picks up Dockerfile y nginx.conf)
  └─ Reinicia los contenedores

composer install --no-dev --optimize-autoloader
  └─ Instala dependencias PHP sin paquetes de desarrollo

php artisan migrate --force
  └─ Aplica migraciones pendientes en producción

php artisan optimize
  └─ Genera caché de config, rutas y vistas

php artisan storage:link
  └─ Crea enlace simbólico public/storage → storage/app/public
```

## Ejecutar el despliegue

### Desde la máquina local (recomendado)

```bash
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="cd /opt/directorio && sudo bash deploy.sh"
```

### Conectado directamente al servidor

```bash
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b
cd /opt/directorio
sudo bash deploy.sh
```

## Verificación post-despliegue

```bash
# Ver estado de los contenedores
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="docker compose -f /opt/directorio/docker-compose.prod.yml ps"

# Ver logs recientes del app
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="docker logs directorio_app --tail=50"

# Ver logs de nginx del host (si hay configurado)
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="sudo tail -50 /var/log/nginx/error.log"
```

Lista de verificación manual:
- [ ] Home (`/`) carga correctamente con grid de negocios
- [ ] Filtro por categoría navega a `/categoria/{slug}`
- [ ] Detalle de negocio abre en `/negocio/{slug}`
- [ ] Panel admin (`/admin`) responde con Filament
- [ ] Las imágenes de negocios cargan desde `/storage/...`

## Variables de entorno

El archivo `.env` **no está en git**. Debe existir en el servidor en `/opt/directorio/.env` con al menos:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=directorio
DB_USERNAME=root
DB_PASSWORD=<password>

FILESYSTEM_DISK=public
```

> `DB_HOST=mysql` porque es el nombre del servicio en `docker-compose.prod.yml`.

## Comandos de diagnóstico

```bash
# Entrar al contenedor de la app
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="docker exec -it directorio_app bash"

# Correr artisan manualmente
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="docker compose -f /opt/directorio/docker-compose.prod.yml exec laravel-app php artisan <comando>"

# Limpiar caché si algo falla
gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
  --command="docker compose -f /opt/directorio/docker-compose.prod.yml exec -T laravel-app php artisan optimize:clear"
```

## Rollback

No hay rollback automático. Para revertir un despliegue fallido:

```bash
# En el servidor
cd /opt/directorio
sudo git log --oneline -10          # identificar el commit anterior
sudo git checkout <commit-hash>     # revertir código
sudo bash deploy.sh                 # redesplegar
```

O crear un commit de revert localmente, hacer push, y correr `deploy.sh` de nuevo.
