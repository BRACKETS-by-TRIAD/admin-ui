mix.js(['resources/js/admin/admin.js'], 'public/js')
    .sass('resources/sass/admin/admin.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
}