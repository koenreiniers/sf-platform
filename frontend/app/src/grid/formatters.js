import { GridFormatter } from 'raw-grid/extensions';

GridFormatter.set('qty', {
    format: function(value) {

        var decimalSeparator = '.';

        if(value.indexOf(decimalSeparator) >= 0) {
            value = value.replace(/0+$/,'');
        }
        value = value.replace(/\.+$/,'');
        return value;
    }
});

GridFormatter.set('state', {
    format: function(value) {
        var map = {
            processing: 'info',
            completed: 'success',
            cancelled: 'warning',
        };
        var level = map[value] || 'default';
        return '<span class="label label-'+level+'">'+value+'</span>';
    }
});
