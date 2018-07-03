// @var Raw
import { http } from 'raw';
import { GridActions } from 'raw-grid/extensions';

function resolveValue(value, record)
{
    if(value.indexOf(':') === 0) {
        value = record[value.substr(1)];
    }
    return value;
}

function extractUrl(options, item) {
    if(options.url) {
        return resolveValue(options.url, item);
    }
    if(options.route) {
        var params = options.routeParams || {};

        var args = {};
        for(var paramName in params) {
            var propertyName = params[paramName];
            var value = item[propertyName];
            args[paramName] = value;
        }

        return Routing.generate(options.route, args);

    }
    throw 'Cannot extract url';
}

GridActions.action('navigate', {
    build: function(action) {
        action.icon = 'chevron-right';
        action.classes = ['btn-default btn-icon'];
    },
    execute: function(options, item) {
        var url = extractUrl(options, item);
        window.location = url;
    },
});

GridActions.action('view', GridActions.action('navigate').extend({
    build: function(action) {
        action.icon = 'eye';
    },
}));

GridActions.action('edit', GridActions.action('navigate').extend({
    build: function(action) {
        action.icon = 'pencil';
    },
}));

GridActions.action('delete', {
    build: function(action) {
        action.icon = 'trash';
        action.iconClasses = ['fa', 'fa-'+action.icon];
        action.classes = ['btn-danger btn-icon'];
    },
    execute: function(options, item, grid) {
        var url = extractUrl(options, item);
        if(options.ajax) {
            http.delete(url).then(function(){
                grid.fetchItems();
            });
        } else {
            window.location = url;
        }


    },
});
