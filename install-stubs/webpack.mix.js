mix.js(['resources/assets/admin/js/admin.js'], 'public/build/admin/js')
    .sass('resources/assets/admin/scss/app.scss', 'public/build/admin/css');

if (mix.inProduction()) {
    mix.version();
}