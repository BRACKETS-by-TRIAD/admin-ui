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
            default: function() {
                return this.locales[0];
            }
        },
        'sendEmptyLocales': {
            type: Boolean,
            default: function() {
                return true;
            }
        },
        'default': {
            type: Object,
            default: function() {
                return {};
            }
        },
    },

    created: function() {
        if (!!this.locales && this.locales.length > 0) {
            let form = this.form
            this.locales.map(function(l) {
                if (!_.has(form, l)) {
                    _.set(form, l, {})
                }
            })
        }
    },

    data: function() {
        return {
            form: this.default,
            isFormLocalized: false,
            currentLocale: 'sk',
        }
    },

    computed: {
        otherLocales: function() {
            return this.locales.filter(x => x != this.defaultLocale);
        },
    },

    methods: {
        onSubmit() {
            return this.$validator.validateAll()
                .then(result => {
                    if (!result) {
                        return false;
                    }

                    var data = this.form;
                    if (!this.sendEmptyLocales) {
                        data = _.omit(this.form, this.locales.filter(locale => _.isEmpty(this.form[locale])));
                    }

                    axios.post(this.action, data)
                        .then(response => this.onSuccess(response.data))
                        .catch(errors => this.onFail(errors.response.data))
                });
        },
        onSuccess(data) {
            window.location.replace(data.redirect)
        },
        onFail(errors) {
            var bag = this.$validator.errorBag;
            Object.keys(errors).map(function(key) {
                var splitted = key.split('.', 2);
                if (splitted.length > 1) {
                    bag.add(splitted[0]+'_'+splitted[1], errors[key][0], null);
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