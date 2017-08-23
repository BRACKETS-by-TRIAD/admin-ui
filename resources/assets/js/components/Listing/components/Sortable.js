const Sortable = {
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
            default: function() {
                return this.$parent.loadData();
            }
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

            this.callback();

            // FIXME callback should have return a Promise which can success or end with an error
        },

    },

    template:
            '<th>' +
                '<a @click.stop="sort(column)"><span class="fa" :class="{\'fa-sort-amount-asc\': orderBy.column == column && orderBy.direction == \'asc\', \'fa-sort-amount-desc\': orderBy.column == column && orderBy.direction == \'desc\' }"></span> <slot></slot></a>' +
            '</th>',
}

export default Sortable;
