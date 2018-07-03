import Vue from 'vue';
import { locator } from 'raw/templating';
import { merge, deepCopy } from 'raw/std';

let Component = Vue.component('raw-widget-form', {
    props: ['widget'],
    template: locator.locate('widget/edit'),
    data: function() {

        var widget = this.widget;

        var formConfig = {
            type: 'form',
            children: {
                title: {
                    type: 'text',
                },
                settings: {
                    type: 'form',
                    children: {},
                },
            },
        };
        var formData = deepCopy(widget);

        return {
            formName: 'widget',
            formData: formData,
            formConfig: formConfig,
        };
    },
    created: function() {
        this.updateSettingsForm();
    },
    methods: {
        updateSettingsForm: function() {
            if(!this.widget.type) {
                return;
            }
            var widgetType = Raw.widget(this.widget.type);
            this.formConfig.children.settings.children = widgetType.settingsForm;
        },
        save: function() {

            //console.log(this.formData);
            //console.log(this.widget);
            //
            //this.widget = this.formData;
            //this.formData = deepCopy(this.widget);

            merge(this.widget, this.formData);

            this.formData = deepCopy(this.widget);

            this.$emit('save', this.widget);

            //this.widget = this.formData;




        },
    },
});

export default Component;