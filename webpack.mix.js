const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin-lte.js', 'public/js')
    // .js('resources/js/datatable-bundle.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/datatable-bundle.scss', 'public/css')
    .sourceMaps();
