module.exports = {

    props: {
        'action': {
            type: String,
            required: true
        },
        'data': {
            type: Object,
            default: function() {
                return {};
            }
        },
    },

    created: function() {
        this.initForm(this.data);
    },

    data: function() {
        return {
            datePickerConfig: {
                format: 'YYYY-MM-DD',
                altInput: true,
                altFormat: 'd.m.Y'
            },
            timePickerConfig: {
                enableTime: true,
                noCalendar: true,
                time_24hr: true,
                enableSeconds: true,
                format: 'kk:mm:ss',
                altInput: true,
                altFormat: 'H:i:S'
            },
            datetimePickerConfig: {
                enableTime: true,
                time_24hr: true,
                enableSeconds: true,
                format: 'YYYY-MM-DD kk:mm:ss',
                altInput: true,
                altFormat: 'd.m.Y H:i:S'
            }
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
        },
        initForm: function(data) {
            if(typeof this.form == typeof undefined) {
                console.error('You do not specified form');
            }
            this.form = data;
            // this.form.activated = data.activated;
            // this.form.first_name = data.first_name;
            // ...
        }
    }
};