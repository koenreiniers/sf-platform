import { locator } from 'raw/templating';
import Vue from 'vue';

let Widget = Raw.widget('welcome_message', {
    label: 'Welcome message widget',
    description: 'Shows a welcome message',
    component: 'raw-widget-welcome-message',
    defaultSettings: {
        message: '',
    },
    settingsForm: {
        message: {
            type: 'text',
        },
    },
});

Vue.component('raw-widget-welcome-message', {
    props: ['widget'],
    template: locator.locate('widgets/welcome_message'),
    watch: {
        'widget.settings': {
            deep: true,
            handler: function(newValue, oldValue) {

            }
        },
    },
    data: function() {
        var widget = this.widget;
        return {
            widget: widget,
            message: widget.settings.message,
        };
    },
    methods: {

    },
});

export default Widget;