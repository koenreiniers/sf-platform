import { locator } from 'raw/templating';
import { http, router } from 'raw';
import Vue from 'vue';

Raw.widget('example', {
    label: 'Example widget',
    description: 'This is an example widget',
    component: 'raw-example-widget',
});

Vue.component('raw-example-widget', {
    props: ['widget', 'dashboard'],
    template: locator.locate('widgets/example'),
    data: function() {

        var widget = this.widget;

        return {
        };
    },
});