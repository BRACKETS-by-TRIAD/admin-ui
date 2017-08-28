const userLanguage = document.documentElement.lang;

const BaseForm = {

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
                return (this.locales instanceof Array && this.locales.length > 0) ? this.locales[0] : '';
            }
        },
        'sendEmptyLocales': {
            type: Boolean,
            default: function() {
                return true;
            }
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

        if (!_.isEmpty(this.data)) {
            this.form = this.data;
        }
    },

    data: function() {
        return {
            form: {},
            isFormLocalized: false,
            currentLocale: 'sk',
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
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            }
        }
    },

    computed: {
        otherLocales: function() {
            return this.locales.filter(x => x != this.defaultLocale);
        },
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

                    var data = this.form;
                    if (!this.sendEmptyLocales) {
                        data = _.omit(this.form, this.locales.filter(locale => _.isEmpty(this.form[locale])));
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

export default BaseForm