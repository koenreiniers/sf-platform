import $ from 'jquery';
import { GridSources } from 'raw-grid/extensions';

GridSources.source('url', {
    async: true,
    getItems: function(grid, options, args) {

        return new Promise((resolve, reject) => {
            var method = options.method || 'GET';

            var data = {};
            data[grid.gridName] = args;

            $.ajax({
                url: options.url,
                data: data,
                method: method,
            }).done(function(response, a, b){


                var totalCount = b.getResponseHeader('X-Total-Count');

                grid.pagination.totalCount = totalCount;

                resolve(response);

            });;
        });



        return defer;
    }
});
