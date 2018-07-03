require('gridstack.jquery-ui');

import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';
import $ from 'jquery';




var Component = Vue.component('raw-dashboard', {
    props: ['dashboard'],
    template: locator.locate('dashboard'),
    watch: {
        'dashboard.id': function(newValue, oldValue) {


            var url = router.generate('raw_dashboard.dashboard.view', {
                id: newValue,
            }, true);

            window.location.href = url;
        },
        'dateRange': {
            deep: true,
            handler: function(newValue) {
                this.dashboard.dateRange.start = newValue.start;
                this.dashboard.dateRange.end = newValue.end;
            },
        },
    },
    data: function() {


        this.selectChoices = {
            '<i class="fa fa-flag-o"></i> flag-o': 'flag-o',
            'B': 'b',
        };

        this.reload();

        var widgetTypes = Raw.getWidgetTypes();

        var comp = this;

        http.get(router.generate('raw_dashboard.api.dashboard.index')).then(function(response){
            comp.dashboards = response.data;
        });

        return {
            newWidget: null,
            widgets: [],
            widgetTypes: widgetTypes,
            selectedWidgetType: null,
            editingName: false,
            dashboards: [],
            addingWidgets: false,
            selectedIcon: 'flag-o',
        };
    },
    mounted: function() {
        var $elm = jQuery(this.$el).find('.grid-stack');
        var comp = this;

        $elm.on('change', function(e, items){

            for(var k in items) {
                var item = items[k];

                var $item = $(item.el);

                var data = {
                    x: item.x,
                    y: item.y,
                    width: item.width,
                    height: item.height,
                };
                var widgetId = $item.data('widget-id');
                var widget = null;
                comp.widgets.forEach(function(w){
                    if(w.id === widgetId) {
                        widget = w;
                    }
                });
                widget.width = item.width;
                widget.height = item.height;
                widget.x = item.x;
                widget.y = item.y;

                var url = router.generate('raw_dashboard.api.widget.update', {
                    id: widgetId,
                });
                http.patch(url, data).then(function(response){
                    //console.log(response);
                });

            }

        });
    },
    updated: function()
    {
        var $elm = jQuery(this.$el).find('.grid-stack');
        $elm.data('gridstack', null);
        $elm.gridstack({
            cellHeight: 30,
            draggable: {
                handle: '.grid-stack-handle',
            },
        });
    },
    methods: {
        addWidgets: function() {
            this.addingWidgets = !this.addingWidgets;
        },
        editName: function() {
            this.editingName = true;
        },
        saveName: function() {

            var comp = this;

            var url = router.generate('raw_dashboard.api.dashboard.update', {
                id: this.dashboard.id,
            });
            http.patch(url, {
                name: this.dashboard.name,
            }).then(function(response){
                comp.editingName = false;
            });
        },
        selectWidgetType: function(widgetType) {
            this.selectedWidgetType =  widgetType;
            this.addWidget(widgetType.name);
        },
        reload: function() {
            var comp = this;

            var url = router.generate('raw_dashboard.api.dashboard.widgets', {
                id: this.dashboard.id,
            });
            http.get(url).then(function(response){
                var widgets = response.data;


                comp.widgets = widgets;
            });


        },
        isResizable: function(widget)
        {
            var widgetTypes = Raw.getWidgetTypes();
            return widgetTypes[widget.type].resizable;
        },
        createWidget: function(widgetType)
        {
            var comp = this;

            var url = router.generate('raw_dashboard.api.widget.create');

            var bottom = 0;
            for(var k in this.widgets) {
                bottom = Math.max(bottom, this.widgets[k].y + this.widgets[k].height);
            }

            var data = {
                type: widgetType.name,
                dashboard: this.dashboard.id,
                x: 0,
                y: bottom,
                width: widgetType.width,
                height: widgetType.height,
            };

            http.post(url, data).then(function(response){

                var id = response.data.id;

                comp.reload();
            });
        },

        addWidget: function(type) {


            var widget = {
                type: null,
                dashboard: this.dashboard.id,
                settings: {},
            };


            this.newWidget = widget;
        },
        remove: function(widget) {
            var i = this.widgets.indexOf(widget);
            this.widgets.splice(i, 1);
        },
    },
});

export default Component;