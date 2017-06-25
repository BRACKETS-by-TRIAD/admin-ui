module.exports = {

    props: {
        'action': {
            type: String,
            required: true
        },
        'default': {
            type: Object,
            default: function() {
                return {};
            }
        },
    },

    data: function() {
        return {
            form: this.default
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
            window.location.replace(data.redirect)
        },
        onFail(errors) {
            Object.keys(errors).map(key => this.$validator.errorBag.add(key, errors[key][0]));
        }
    }
};