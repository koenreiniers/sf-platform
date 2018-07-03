import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';

let Widget = Raw.widget('grid', {
    label: 'Grid widget',
    description: 'Used to render a grid',
    component: 'raw-grid-widget',
    defaultSettings: {
        gridName: '',
    },
    settingsForm: {
        gridName: {
            type: 'text',
            required: true,
        },
    },
});
export default Widget;
Vue.component('raw-grid-widget', {
    props: ['widget'],
    template: locator.locate('widgets/grid'),
    watch: {
        'widget.settings': {
            deep: true,
            handler: function(newValue, oldValue) {
                this.update(newValue);
            }
        },
    },
    methods: {
        update: function(settings)
        {
            var comp = this;
            if(!settings.gridName) {
                return;
            }
            http.get(router.generate('raw_grid.grid.view', {
                gridName: settings.gridName,
            })).then(function(response){
                var config = response.data;
                comp.config = config;
                comp.loaded = true;
            });
        }
    },
    data: function() {

        var widget = this.widget;
        var comp = this;
        var parameters = {};

        this.update(widget.settings);

        return {
            widget: widget,
            parameters: parameters,
            loaded: false,
        };
    },
});

