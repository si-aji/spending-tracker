const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/assets/js')
    .css('resources/css/app.css', 'public/assets/css')
    .sourceMaps();

// Dashboard Admin
mix.sass('resources/assets/corporate-ui/assets/css/custom.scss', 'public/assets/corporate-ui/css/siaji.css').version();
mix.copyDirectory('resources/assets/corporate-ui/assets', 'public/assets/corporate-ui');
mix.copy('resources/assets/corporate-ui/CHANGELOG.md', 'public/assets/corporate-ui');
mix.copy('resources/assets/corporate-ui/README.md', 'public/assets/corporate-ui');

// Fontawesome
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/css', 'public/assets/font/fontawesome/css');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/js', 'public/assets/font/fontawesome/js');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/svgs', 'public/assets/font/fontawesome/svgs');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/assets/font/fontawesome/webfonts');
