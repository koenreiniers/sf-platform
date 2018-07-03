// @var Raw
import Vue from 'vue';
import { locator } from 'raw/templating';

let Component = Vue.component('raw-create-widget', {
    props: [],
    template: locator.locate('widget/create'),
    data: function() {

        var widgetTypes = Raw.getWidgetTypes();

        return {
            widgetTypes: widgetTypes,
        };
    },
    methods: {
        addWidget: function(widgetType) {
            this.$emit('add', widgetType);
        },
        close: function() {
            this.$emit('close');
        },
    },
});

export default Component;