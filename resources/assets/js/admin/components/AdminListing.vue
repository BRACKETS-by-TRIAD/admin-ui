<template>
    <div>
        <slot name="before-table"></slot>

        <slot name="pagination" :pagination="pagination">
            <select v-model="pagination.state.per_page" v-if="!hidePerPage && !hidePagination">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="100">100</option>
            </select>
        </slot>

        <slot name="filters" :filter="filter"></slot>

        <table class="table table-striped table-hover" v-if="collection">
            <slot name="thead" :orderBy="orderBy" :sort="sort"></slot>
            <tbody>
                <slot name="row" v-for="item in collection" :item="item"></slot>
            </tbody>
        </table>

        <h3 class="text-center" v-else="collection">Loading data..</h3>

        <span v-if="collection">Displaying from {{ this.pagination.state.from }} to {{ this.pagination.state.to }} of total {{ this.pagination.state.total }} items.</span>

        <!-- TODO how to add push state to this pagination so the URL will actually change? we need JS router - do we want it? -->
        <admin-pagination :pagination="pagination.state" :callback="loadData" :options="pagination.options"
                    v-if="collection && !hidePagination && pagination.state.last_page > 1"></admin-pagination>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                pagination : {
                    state: {
                        per_page: 10,    // required
                        current_page: 1, // required
                        last_page: 1,    // required
                        from: 1,
                        to: 10           // required
                    },
                    options: {
                        alwaysShowPrevNext: true
                    },
                },
                orderBy: {
                    column: 'id',
                    direction: 'asc',
                },
                filters: {},
                collection: null,
            }
        },
        props: {
            'url': {
                type: String,
                required: true
            },
            'hidePerPage': {
                type: Boolean,
                default: false,
            },
            'hidePagination': {
                type: Boolean,
                default: false,
            },
            'data': {
                type: Object,
                default: function() {
                    return null;
                }
            }
        },
        components: {
            'admin-pagination': require('vue-bootstrap-pagination'),
        },

        created: function() {
            if (this.data != null){
                this.populateCurrentStateAndData(this.data);
            } else {
                this.loadData();
            }
        },

        methods: {

            sort(newColumn) {
                if (this.orderBy.column == newColumn) {
                    this.orderBy.direction = this.orderBy.direction == 'asc' ? 'desc' : 'asc';
                } else {
                    this.orderBy.column = newColumn;
                    this.orderBy.direction = 'asc'; // I guess we do want to reset direction when changing column, but I'm not sure :)
                }
                this.loadData();
            },

            loadData (resetCurrentPage) {
                let options = {
                    params: {
                        per_page: this.pagination.state.per_page,
                        page: this.pagination.state.current_page,
                        orderBy: this.orderBy.column,
                        orderDirection: this.orderBy.direction,
                    }
                };

                if (resetCurrentPage === true) {
                    options.params.page = 1;
                }

                Object.assign(options.params, this.filters);

                axios.get(this.url, options).then(response => this.populateCurrentStateAndData(response.data.data), error => {
                    // TODO handle error
                });
            },

            filter(column, value) {
                if (value == '') {
                    delete this.filters[column];
                } else {
                    this.filters[column] = value;
                }
                // when we change filter, we must reset pagination, because the total items count may has changed
                this.loadData(true);
            },

            populateCurrentStateAndData(object) {
                // FIXME Avoid mutating a prop directly since the value will be overwritten whenever the parent component re-renders. Instead, use a data or computed property based on the prop's value. Prop being mutated: "collection" found in
                this.collection = object.data;
                this.pagination.state.current_page = object.current_page;
                this.pagination.state.last_page = object.last_page;
                this.pagination.state.total = object.total;
                this.pagination.state.per_page = object.per_page;
                this.pagination.state.to = object.to;
                this.pagination.state.from = object.from;
            }

        }
    }
</script>

<style>
    table th a {
        cursor: pointer;
    }
</style>