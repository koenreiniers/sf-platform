import Vue from 'vue';
import { locator } from 'raw/templating';
import { Flash } from 'raw/misc';

let Pagination = {
    template: locator.locate('grid/pagination'),
    props: ['data'],
    data: function() {

        var data = this.data;

        return data;
    },
    methods: {
        firstPage: function() {
            this.page = 1;
        },
        lastPage: function() {
            this.page = this.amountOfPages;
        },
        nextPage: function() {
            Flash.flash('success', 'yolog');
            this.page = Math.min(this.page + 1, this.amountOfPages);
        },
        previousPage: function() {
            this.page = Math.max(1, this.page - 1);
        },
    },
};

export default Pagination;