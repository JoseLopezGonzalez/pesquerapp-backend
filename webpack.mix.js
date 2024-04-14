const mix = require('laravel-mix');

// Requerir TailwindCSS para usarlo en la compilación de PostCSS
require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'), // Añade TailwindCSS como un plugin de PostCSS
   ]);

// Si también estás usando Sass, puedes incluirlo así
// mix.sass('resources/sass/app.scss', 'public/css');

// Opciones adicionales para mejorar la experiencia de desarrollo
mix.browserSync('localhost:8000'); // Asegúrate de ajustar esta URL a tu entorno local

// Configuraciones para la versión de producción
if (mix.inProduction()) {
    mix.version(); // Agrega versionado de cache a los archivos compilados
}
