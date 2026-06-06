import { cpSync, mkdirSync } from 'fs';

mkdirSync('public/vendor/leaflet', { recursive: true });
cpSync('node_modules/leaflet/dist/leaflet.js', 'public/vendor/leaflet/leaflet.js');
cpSync('node_modules/leaflet/dist/leaflet.css', 'public/vendor/leaflet/leaflet.css');
cpSync('node_modules/leaflet/dist/images', 'public/vendor/leaflet/images', { recursive: true });

mkdirSync('public/vendor/mdi/css', { recursive: true });
mkdirSync('public/vendor/mdi/fonts', { recursive: true });
cpSync('node_modules/@mdi/font/css/materialdesignicons.min.css', 'public/vendor/mdi/css/materialdesignicons.min.css');
cpSync('node_modules/@mdi/font/fonts', 'public/vendor/mdi/fonts', { recursive: true });
