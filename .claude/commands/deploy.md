Despliega los cambios más recientes al servidor de producción en GCP.

## Contexto del proyecto

- **Instancia GCP**: `directorio-pamplona`, zona `southamerica-east1-b`
- **Directorio en servidor**: `/opt/directorio` (owner: root, requiere `sudo`)
- **Script de despliegue**: `deploy.sh` en la raíz del proyecto
- **Compose de producción**: `docker-compose.prod.yml`

El script `deploy.sh` hace: `git pull` → build frontend con Node en Docker → reconstruye contenedores → `composer install` → `php artisan migrate --force` → `php artisan optimize` → `storage:link`.

## Pasos a seguir

1. Verificar que los cambios locales están commiteados y pusheados a `origin/main`:
   ```bash
   git status
   git log origin/main..HEAD --oneline
   ```
   Si hay commits sin pushear, notificar al usuario antes de continuar.

2. Ejecutar el despliegue en el servidor:
   ```bash
   gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
     --command="cd /opt/directorio && sudo bash deploy.sh"
   ```
   El timeout debe ser de al menos 5 minutos (300000ms) porque el build de Node puede tardar.

3. Verificar el estado de los contenedores:
   ```bash
   gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
     --command="docker compose -f /opt/directorio/docker-compose.prod.yml ps"
   ```

4. Si el usuario pasa `--logs` como argumento o si algo falla, mostrar los últimos logs del contenedor:
   ```bash
   gcloud compute ssh directorio-pamplona --zone=southamerica-east1-b \
     --command="docker logs directorio_app --tail=80"
   ```

5. Reportar el resultado: si todo salió bien, confirmar en 1-2 líneas. Si algo falló, mostrar el error relevante y sugerir el comando de diagnóstico apropiado (ver `deploy.md`).

## Notas importantes

- El directorio pertenece a root → todos los comandos en el servidor necesitan `sudo`.
- Si `deploy.sh` no existe en el servidor (primera vez), hacer primero `sudo git pull origin main`.
- Si el usuario menciona que falló por permisos de git, el fix es: `sudo git config --global --add safe.directory /opt/directorio`.
- Para diagnóstico de errores en producción consultar `deploy.md` en la raíz del proyecto.
