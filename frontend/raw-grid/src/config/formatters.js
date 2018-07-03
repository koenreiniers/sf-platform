import { GridFormatter } from 'raw-grid/extensions';

GridFormatter.set('date', {
    format: function(value) {
        return value.date;
    }
});

GridFormatter.set('boolean', {
    format: function(value) {
        return value ? 'Yes' : 'No';
    },
});

GridFormatter.set('enabled', {
    format: function(value) {
        return value ? '<span class="label label-success"><i class="fa fa-check"></i> Enabled</span>' : '<span class="label label-danger">Disabled</span>';
    }
});
