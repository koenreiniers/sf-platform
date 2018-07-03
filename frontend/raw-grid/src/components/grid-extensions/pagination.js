import Grid from '../../classes/grid';
import PaginationComponent from '../../components/pagination';
import Vue from 'vue';
import { flash } from 'raw';

Grid.extend(function(){
    Grid.extendComponent({
        components: {
            pagination: PaginationComponent,
        },
    });

    function updatePagination(data)
    {
        data.startIndex = (data.page - 1) * data.itemsPerPage;
        data.start = Math.min(data.startIndex + 1, data.totalCount);
        data.end = Math.min(data.startIndex + data.itemsPerPage, data.totalCount);
    }

    Grid.extendComponent({
        watch: {
            'pagination.itemsPerPage': function(newValue, oldValue) {
                this.fetchItems();
                this.pagination.amountOfPages = Math.ceil(this.pagination.totalCount / this.pagination.itemsPerPage);
                this.pagination.page = Math.min(this.pagination.page, this.pagination.amountOfPages);
                this.pagination.page = Math.max(1, this.pagination.page);
            },
            'pagination.page': function(newValue, oldValue) {
                this.fetchItems();
            },
            'pagination.totalCount': function(newValue, oldValue) {
                this.pagination.amountOfPages = Math.ceil(this.pagination.totalCount / this.pagination.itemsPerPage);
                this.pagination.page = Math.min(this.pagination.page, this.pagination.amountOfPages);
                this.pagination.page = Math.max(1, this.pagination.page);
            },
            'pagination': {
                deep: true,
                handler: function() {

                    var pagination = this.pagination;

                    updatePagination(pagination);

                }
            },
        },
        beforeCreate: function() {
            this.$on('fetch', function(args, options){
                args.pagination = {
                    page: this.pagination.page,
                    itemsPerPage: this.pagination.itemsPerPage,
                };
            });
        },
        data: function(){
            return {
                pagination: {
                    page: 1,
                    itemsPerPage: 10,
                    amountOfPages: 1,
                    totalCount: 0,
                    start: 0,
                    end: 0,
                },
            };
        },
    });
});