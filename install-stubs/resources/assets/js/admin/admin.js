// core stuff like axios, ...
require('../bootstrap')

// brackets/admin overloaded files
require('./components/bootstrap');

// custom files
require('./bootstrap');

window.moment = require('moment');

Vue.use(require('vee-validate')
    , { strict: true }
);

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