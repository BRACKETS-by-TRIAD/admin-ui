import moment from 'moment';
import VeeValidate from 'vee-validate';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import VueQuillEditor from 'vue-quill-editor';
import Notifications from 'vue-notification';

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

// modal
import VModal from 'vue-js-modal'
Vue.use(VModal, { dialog: true });

// multiselect
import Multiselect from 'vue-multiselect'
Vue.component(Multiselect);

new Vue({
    mixins: [require('admin')],
});