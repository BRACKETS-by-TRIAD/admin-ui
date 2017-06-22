module.exports = {
    props: {
        'parentProps': {
            type: Object,
            required: true
        },
        'column': {
            type: String,
            required: true
        },
    },

    template: function() {
        return
            '<th>' +
                '<a @click.stop="parentProps.sort(column)"><span class="fa" :class="{\'fa-sort-amount-asc\': parentProps.orderBy.column == column && parentProps.orderBy.direction == \'asc\', \'fa-sort-amount-desc\': parentProps.orderBy.column == column && parentProps.orderBy.direction == \'desc\' }"></span> <slot></slot></a>' +
            '</th>';
    }
}