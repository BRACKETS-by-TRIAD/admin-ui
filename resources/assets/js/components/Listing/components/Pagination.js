const Pagination = {
    template: `<nav>
        <ul class="pagination" v-if="pagination.last_page > 1" :class="sizeClass">
            <li v-if="showPrevious()" :class="{ 'disabled' : pagination.current_page <= 1 }">
                <span v-if="pagination.current_page <= 1" class="page-link">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </span>

                <a href="#" v-if="pagination.current_page > 1 " :aria-label="config.ariaPrevioius" @click.prevent="changePage(pagination.current_page - 1)">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </a>
            </li>

            <li v-for="num in array" :class="{ 'active': num === pagination.current_page }">
                <a href="#" @click.prevent="changePage(num)">{{ num }}</a>
            </li>

            <li v-if="showNext()" :class="{ 'disabled' : pagination.current_page === pagination.last_page || pagination.last_page === 0 }">
                <span v-if="pagination.current_page === pagination.last_page || pagination.last_page === 0" class="page-link">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </span>

                <a href="#" v-if="pagination.current_page < pagination.last_page" :aria-label="config.ariaNext" @click.prevent="changePage(pagination.current_page + 1)">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </a>
            </li>
        </ul>
    </nav>`,
    props: {
        pagination: {
            type: Object,
            default: function() {
                return this.$parent.pagination.state;
            }
        },
        callback: {
            type: Function,
            default: function() {
                return this.$parent.loadData();
            }
        },
        options: {
            type: Object,
            default: function() {
                if (!!this.$parent.pagination.options) {
                    return this.$parent.pagination.options;
                } else {
                    return {};
                }
            }
        },
        size: {
            type: String
        }
    },
    computed: {
        array () {
            if (this.pagination.last_page <= 0) {
                return [];
            }

            let from = this.pagination.current_page - this.config.offset;
            if (from < 1) {
                from = 1;
            }

            let to = from + (this.config.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            let arr = [];
            while (from <=to) {
                arr.push(from);
                from++;
            }

            return arr;
        },
        config () {
            return Object.assign({
                offset: 3,
                ariaPrevious: 'Previous',
                ariaNext: 'Next',
                previousText: '«',
                nextText: '»',
                alwaysShowPrevNext: false
            }, this.options);
        },
        sizeClass () {
            if (this.size === 'large') {
                return 'pagination-lg';
            } else if(this.size === 'small') {
                return 'pagination-sm';
            } else {
                return '';
            }
        }
    },
    watch: {
        'pagination.per_page' (newVal, oldVal) {
            if (+newVal !== +oldVal) {
                this.$cookie.set('per_page', newVal);
                this.callback();
            }
        }
    },
    methods: {
        showPrevious () {
            return this.config.alwaysShowPrevNext || this.pagination.current_page > 1;
        },
        showNext () {
            return this.config.alwaysShowPrevNext || this.pagination.current_page < this.pagination.last_page;
        },
        changePage (page) {
            if (this.pagination.current_page === page) {
                return;
            }

            this.$set(this.pagination, 'current_page', page);
            this.callback();
        }
    }
};

export default Pagination;