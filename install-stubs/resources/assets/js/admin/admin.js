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

new Vue({
    mixins: [require('admin')],
});