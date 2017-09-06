import VeeValidate from 'vee-validate';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import VueQuillEditor from 'vue-quill-editor';
import Notifications from 'vue-notification';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import VModal from 'vue-js-modal'
import Vue from 'vue';

import Admin from 'admin';
import bootstrap from './bootstrap';
import coreui from '../coreui/js/app.js';
import bootstrapComponents from './components/bootstrap';
import index from './index';

Vue.use(VeeValidate, {
	strict: true
});
Vue.use(VueQuillEditor);
Vue.use(Notifications);
Vue.component('datetime', flatPickr);
Vue.use(VModal, { dialog: true });
Vue.component('multiselect', Multiselect);

new Vue({
	mixins: [Admin],
});