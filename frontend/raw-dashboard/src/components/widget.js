// @var Raw
import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';

let Component = Vue.component('raw-widget', {
    props: ['widget', 'dashboard'],
    template: locator.locate('widget'),
    data: function() {

        var widget = this.widget;
        var widgetType = Raw.widget(widget.type);

        return {
            widgetType: widgetType,
            showSettings: false,
        };
    },
    methods: {
        remove: function() {

            var confirmed = confirm('Are you sure?');

            if(!confirmed) {
                return;
            }

            var comp = this;
            var widget = this.widget;
            var url = router.generate('raw_dashboard.api.widget.delete', {
                id: widget.id,
            });
            http.delete(url).then(function(response){
                comp.$emit('remove', widget);
            });
        },
        update: function(widget) {
            var comp = this;
            var url = router.generate('raw_dashboard.api.widget.update', {
                id: widget.id,
            });
            http.patch(url, widget).then(function(response){
                comp.showSettings = false;
            });
        },
        toggleSettings: function() {
            //$(this.$el).find('[data-settings-modal]').modal('toggle');
            this.showSettings = !this.showSettings;
        },
        getComponentName: function() {

            var widgetType = Raw.widget(this.widget.type);
            return widgetType.component;
        },
    },
});

export default Component;