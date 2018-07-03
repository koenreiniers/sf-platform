import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';

VueForm.type('text', {
    component: 'raw-form-text',
});

let Component = Vue.component('raw-form-text', Vue.component('raw-form').extend({
    template: locator.locate('form/simple'),
    data: function(){
        return {
            inputType: 'text',
        };
    },
}));

export default Component;