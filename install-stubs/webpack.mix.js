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
	.extract([
		'vue',
		'jquery',
		'vee-validate',
		'axios',
		'vue-notification',
		'vue-quill-editor',
		'vue-flatpickr-component',
		'moment',
		'lodash'
	])
	.version();