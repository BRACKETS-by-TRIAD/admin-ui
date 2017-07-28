const userLanguage = document.documentElement.lang;

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
        if (!!this.locales && this.locales.length > 0) {
            let form = this.form
            // this.locales.map(function(l) {
            //     if (!_.has(form, l)) {
            //         _.set(form, l, {})
            //     }
            // })
        }
    },

    data: function() {
        return {
            form: this.data,
            datePickerConfig: {
                dateFormat: 'Y-m-d H:i:S',
                altInput: true,
                altFormat: 'd.m.Y',
                locale: userLanguage === 'en' ? null : require("flatpickr/dist/l10n/"+userLanguage+".js")[userLanguage]
            },
            timePickerConfig: {
                enableTime: true,
                noCalendar: true,
                time_24hr: true,
                enableSeconds: true,
                dateFormat: 'H:i:S',
                altInput: true,
                altFormat: 'H:i:S',
                locale: userLanguage === 'en' ? null : require("flatpickr/dist/l10n/"+userLanguage+".js")[userLanguage]
            },
            datetimePickerConfig: {
                enableTime: true,
                time_24hr: true,
                enableSeconds: true,
                dateFormat: 'Y-m-d H:i:S',
                altInput: true,
                altFormat: 'd.m.Y H:i:S',
                locale: userLanguage === 'en' ? null : require("flatpickr/dist/l10n/"+userLanguage+".js")[userLanguage]
            },
            wysiwygConfig: {
                placeholder: 'Type a text here',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            }
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
            if (data.redirect) {
                window.location.replace(data.redirect)
            }
        },
        onFail(errors) {
            Object.keys(errors).map(key => this.$validator.errorBag.add(key, errors[key][0]));
        }
    }
};
