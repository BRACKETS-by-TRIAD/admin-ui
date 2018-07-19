import './bootstrap';

import 'vue-multiselect/dist/vue-multiselect.min.css';
import flatPickr from 'vue-flatpickr-component';
import VueQuillEditor from 'vue-quill-editor';
import VueTrumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';
import Notifications from 'vue-notification';
import Multiselect from 'vue-multiselect';
import VeeValidate from 'vee-validate';
import 'flatpickr/dist/flatpickr.css';
import VueCookie from 'vue-cookie';
import { Admin } from 'craftable';
import VModal from 'vue-js-modal'
import Vue from 'vue';

import './app-components/bootstrap';
import './index';

import 'craftable/coreui';

Vue.component('multiselect', Multiselect);
Vue.use(VeeValidate, {strict: true});
Vue.component('datetime', flatPickr);
Vue.component('wysiwyg', VueTrumbowyg);
Vue.use(VModal, { dialog: true });
Vue.use(VueQuillEditor);
Vue.use(Notifications);
Vue.use(VueCookie);

new Vue({
    mixins: [Admin],
});