mix.js(['resources/assets/admin/js/admin.js'], 'public/build/admin/js')
	.webpackConfig({
		resolve: {
			modules: [
				path.resolve(__dirname, 'vendor/brackets/admin-ui/resources/assets/js'),
				// Do not delete this comment, it's used for auto-generation :)
				'node_modules'
			],
		}
	})
	.sass('resources/assets/admin/scss/app.scss', 'public/build/admin/css')
	// There is an issue in Laravel Mix, that does not allow to have multiple extracts, that's why we don't use it yet
	// .extract([
	// 	'vue',
	// 	'jquery',
	// 	'vee-validate',
	// 	'axios',
	// 	'vue-notification',
	// 	'vue-quill-editor',
	// 	'vue-flatpickr-component',
	// 	'moment',
	// 	'lodash'
	// ])
;

if (mix.inProduction()) {
    mix.version();
}