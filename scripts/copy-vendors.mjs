import { cpSync, mkdirSync } from 'fs';

mkdirSync('public/vendor/leaflet', { recursive: true });
cpSync('node_modules/leaflet/dist/leaflet.js', 'public/vendor/leaflet/leaflet.js');
cpSync('node_modules/leaflet/dist/leaflet.css', 'public/vendor/leaflet/leaflet.css');
