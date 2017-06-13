/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../bootstrap')

window.Vue = require('vue')


// import VeeValidate from 'vee-validate';
import AdminListing from './components/AdminListing.vue';
import SortableTh from './components/SortableTh.vue';

//
// Vue.use(VeeValidate)
//
// class Form {
//
//     constructor(data, validator) {
//         this.originalData = data;
//         this.validator = validator;
//
//         for(let field in data) {
//             this[field] = data[field];
//         }
//     }
//
//     submit(url) {
//         return new Promise((resolve, reject) => {
//             axios.post(url, this.data())
//                 .then(response => {
//                     this.onSuccess(response.data);
//                     if (typeof resolve == 'function') {
//                         resolve(response.data);
//                     }
//                 })
//                 .catch(errors => {
//                     this.onFail(errors.response.data);
//                     if (typeof reject == 'function') {
//                         reject(errors.response.data);
//                     }
//                 });
//         })
//     }
//
//     data() {
//         let data = {};
//
//         for (let property in this.originalData) {
//             data[property] = this[property];
//         }
//
//         return data;
//     }
//
//     onSuccess(data) {
//     }
//
//     onFail(errors) {
//         Object.keys(errors).map(key => app.errors.add(key, errors[key][0]))
//     }
// }

const app = new Vue({

    el: '#app',
    //
    // data: {
    //     form: new Form({
    //         title: '',
    //         slug: '',
    //         is_top: ''
    //     })
    // },

    components: {
        AdminListing, SortableTh
    },
    //
    // computed: {
    //     disabled: function() {
    //         return this.errors.any(); // TODO disable form after submit (while the ball is on the server side and keep disabled if submit was successful)
    //     }
    // },
    //
    // methods: {
    //
    //     onSubmit() {
    //         this.form.submit('/admin/post/store')
    //             .then(data => window.location.replace('/admin/post'));
    //     }
    //
    // }
});