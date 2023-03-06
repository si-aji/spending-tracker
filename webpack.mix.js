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
mix.copy('resources/js/custom.js', 'public/assets/corporate-ui/js/siaji.js').version();
mix.copyDirectory('resources/assets/corporate-ui/assets', 'public/assets/corporate-ui');
mix.copy('resources/assets/corporate-ui/CHANGELOG.md', 'public/assets/corporate-ui');
mix.copy('resources/assets/corporate-ui/README.md', 'public/assets/corporate-ui');

/**
 * Plugins
 */
// Fontawesome
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/css', 'public/assets/font/fontawesome/css');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/js', 'public/assets/font/fontawesome/js');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/svgs', 'public/assets/font/fontawesome/svgs');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/assets/font/fontawesome/webfonts');
// Bootstrap
mix.copy('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/assets/plugins/bootstrap/js');
// Choices JS
// Choices Js
mix.copy('node_modules/choices.js/public/assets/scripts/choices.min.js', 'public/assets/plugins/choices.js/choices.min.js').version();
mix.copy('node_modules/choices.js/public/assets/styles/base.min.css', 'public/assets/plugins/choices.js/base.min.css').version();
mix.copy('node_modules/choices.js/public/assets/styles/choices.min.css', 'public/assets/plugins/choices.js/choices.min.css').version();
// Moment JS
mix.copy('node_modules/moment/dist/locale', 'public/assets/plugins/moment/locale');
mix.copy('node_modules/moment/min/moment.min.js', 'public/assets/plugins/moment');
mix.copy('node_modules/moment-timezone/builds/moment-timezone-with-data.min.js', 'public/assets/plugins/moment');
// Nestable
mix.copy('node_modules/nestablejs/dist/nestable.css', 'public/assets/plugins/nestable/nestable.css').version();
mix.js('resources/js/plugins/nestable/script.js', 'public/assets/plugins/nestable/nestable.js').version();
// mix.copy('node_modules/nestablejs/dist/nestable.js', 'public/assets/plugins/nestable/nestable.js').version();
// mix.js('node_modules/nestablejs/src/index.js', 'public/assets/plugins/nestable/nestable.js').version();
// Imask
mix.js('resources/js/plugins/imask/script.js', 'public/assets/plugins/imask/imask.js').version();
// Flatpickr
mix.js('resources/js/plugins/flatpickr/script.js', 'public/assets/plugins/flatpickr/flatpickr.min.js').version();
mix.copy('node_modules/flatpickr/dist/flatpickr.min.css', 'public/assets/plugins/flatpickr/flatpickr.min.css').version();