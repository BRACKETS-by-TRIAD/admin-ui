import moment from 'moment';
import VeeValidate from 'vee-validate';

// core stuff like axios, ...
import bootstrap from './bootstrap';
// brackets/admin overloaded files
import bootstrapComponents from './components/bootstrap';
// custom files
import index from './index';


window.moment = moment;
Vue.use(VeeValidate, {
	strict: true
});


// datepicker
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
Vue.component('datetime', flatPickr);

// wysiwyg
import VueQuillEditor from 'vue-quill-editor';
Vue.use(VueQuillEditor);

// toast
import Notifications from 'vue-notification';
Vue.use(Notifications);

new Vue({
    mixins: [require('admin')],
});