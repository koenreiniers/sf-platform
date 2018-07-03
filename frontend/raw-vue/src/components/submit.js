import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
var Component = {};
export default Component;

(function(Vue, VueForm){

    VueForm.type('submit', {
        component: 'raw-form-submit',
    });

    Vue.component('raw-form-submit', Vue.component('raw-form').extend({
        template: locator.locate('form/button'),
        methods: {
            onClick: function(a, b) {
                this.$emit('submit');
            },
        },
    }));

})(Vue, VueForm);