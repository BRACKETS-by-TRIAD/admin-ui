// core stuff like axios, ...
require('./../bootstrap')

// brackets/admin overloaded files
require('./components/bootstrap');

// custom files
require('./bootstrap');

var admin = new Vue({
    mixins: [require('admin')]
});

