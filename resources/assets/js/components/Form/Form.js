module.exports = {

    props: {
        'action': {
            type: String,
            required: true
        },
        'locales': {
            type: Array
        },
        'defaultLocale': {
            type: String,
        },
        'default': {
            type: Object,
            default: function() {
                var o = {};

                if (!!this.locales && this.locales.length > 0) {
                    this.locales.map(x => o[x] = {});
                }

                return o;
            }
        },
    },

    data: function() {
        return {
            form: this.default,
            isFormLocalized: false,
            currentLocale: 'sk',
        }
    },

    methods: {
        onSubmit() {
            return this.$validator.validateAll()
                .then(result => {
                    if (!result) {
                        return false;
                    }
                    axios.post(this.action, this.form)
                        .then(response => this.onSuccess(response.data))
                        .catch(errors => this.onFail(errors.response.data))
                });
        },
        onSuccess(data) {
            // window.location.replace(data.redirect)
        },
        onFail(errors) {
            var bag = this.$validator.errorBag;
            Object.keys(errors).map(function(key) {
                var splitted = key.split('.', 2);
                if (splitted.length > 1) {
                    bag.add(splitted[1], errors[key][0], null, splitted[0]);
                } else {
                    bag.add(key, errors[key][0]);
                }
            });
        },

        showLocalization() {
            this.isFormLocalized = true;
        },
        hideLocalization() {
            this.isFormLocalized = false;
        }
    }
};