import { http } from 'raw';
import { GridMassActions } from 'raw-grid/extensions';

GridMassActions.massAction('batch_job', {
    execute: function(massAction, ids, records, resolve, reject) {

        var self = this;

        var data = {};
        data[self.name] = {
            mass_action: {
                name: massAction.name,
                ids: ids,
                records: records,
            },
        };

        http.post(massAction.url, data).then(function(response){
            self.fetchItems();
            self.clearSelection();

            resolve();
        });
    },
});

GridMassActions.massAction('export', {
    execute: function(massAction, ids, records, resolve, reject) {
        var url = massAction.url;

        ids = JSON.stringify(ids);


        window.open(url+'?ids='+ids);

        resolve();
    },
});
