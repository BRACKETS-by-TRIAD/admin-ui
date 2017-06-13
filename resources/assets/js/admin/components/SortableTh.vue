<template>
    <th>
        <a @click.stop="parentProps.sort(column)"><span class="glyphicon" :class="{'glyphicon-sort-by-attributes': parentProps.orderBy.column == column && parentProps.orderBy.direction == 'asc', 'glyphicon-sort-by-attributes-alt': parentProps.orderBy.column == column && parentProps.orderBy.direction == 'desc' }"></span> <slot></slot></a>
    </th>
</template>

<script>
    export default {
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
    }
</script>