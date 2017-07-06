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
        getPostData() {
            return this.form
        },
        onSubmit() {
            return this.$validator.validateAll()
                .then(result => {
                    if (!result) {
                        return false;
                    }
                    axios.post(this.action, this.getPostData())
                        .then(response => this.onSuccess(response.data))
                        .catch(errors => this.onFail(errors.response.data))
                });
        },
        onSuccess(data) {
           if(data.success) {
                window.location.replace(data.redirect)
           } 
        },
        onFail(errors) {
            Object.keys(errors).map(key => this.$validator.errorBag.add(key, errors[key][0]));
        }
    }
};