import { readFileSync, writeFileSync } from 'node:fs';

const manifest = JSON.parse(readFileSync('public/build/manifest.json', 'utf8'));
const css = manifest['resources/css/app.css'].file;
const js = manifest['resources/js/app.js'].file;

let html = readFileSync('public/index.html', 'utf8');

const assetTags =
    `<link rel="preload" as="style" href="build/${css}" />` +
    `<link rel="modulepreload" as="script" href="build/${js}" />` +
    `<link rel="stylesheet" href="build/${css}" />` +
    `<script type="module" src="build/${js}"></script>`;

html = html.replace(
    /<link rel="preload" as="style" href="build\/assets\/[^"]+" \/><link rel="modulepreload" as="script" href="build\/assets\/[^"]+" \/><link rel="stylesheet" href="build\/assets\/[^"]+" \/><script type="module" src="build\/assets\/[^"]+"><\/script>/,
    assetTags
);

writeFileSync('public/index.html', html);
console.log('Synced static assets:', { css, js });
