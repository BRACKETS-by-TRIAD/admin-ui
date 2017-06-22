module.exports = {
    props: {
        'column': {
            type: String,
            required: true
        },

        'orderBy': {
            type: Object,
            default: function() {
                return this.$parent.orderBy;
            }
        },

        'callback': {
            type: Function,
        }
    },

    methods: {

        sort: function(column) {
            if (this.orderBy.column == column) {
                this.orderBy.direction = this.orderBy.direction == 'asc' ? 'desc' : 'asc';
            } else {
                this.orderBy.column = column;
                this.orderBy.direction = 'asc'; // I guess we do want to reset direction when changing column, but I'm not sure :)
            }

            if (!!this.callback) {
                this.callback();
            } else {
                this.$parent.loadData();
            }
        },

    },

    template:
            '<th>' +
                '<a @click.stop="sort(column)"><span class="fa" :class="{\'fa-sort-amount-asc\': orderBy.column == column && orderBy.direction == \'asc\', \'fa-sort-amount-desc\': orderBy.column == column && orderBy.direction == \'desc\' }"></span> <slot></slot></a>' +
            '</th>',
}