import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
import { humanize } from 'raw/std';

var Component = Vue.component('form-label', {
    template: locator.locate('form/label'),
    props: ['config'],
    data: function(){


        return {
            label: humanize(this.config.label),
            required: this.config.required,
        };

    },
});

export default Component;