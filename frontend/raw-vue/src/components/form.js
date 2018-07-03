import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';


VueForm.type('form', {
    component: 'raw-form',
});

let Component = Vue.component('raw-form', {
    template: locator.locate('form/form'),
    props: ['name', 'config', 'value'],
    data: function(){

        this.config.label = this.config.label || this.name;
        if(this.config.required === undefined) {
            this.config.required = true;
        }

        return {
            newValue: this.value,
            label: this.config.label,
            required: this.config.required,
        };
    },
    methods: {
        onInput: function(e) {
            this.$emit('input', this.newValue)
        },
        getComponentName: function(childForm) {
            return VueForm.type(childForm.type).component;
        },
        onSubmit: function() {

            this.validate();

            this.$emit('submit', this.newValue);
        },
        validate: function() {

            if(this.required && this.newValue == '') {
                throw 'Required field not filled in';
            }
        },
    },
});

export default Component;