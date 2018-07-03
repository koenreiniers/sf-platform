import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
var Component = {};
export default Component;

(function(Vue, VueForm){

    VueForm.type('integer', {
        component: 'raw-form-integer',
    });

    Vue.component('raw-form-integer', Vue.component('raw-form').extend({
        template: locator.locate('form/simple'),
        data: function(){
            return {
                inputType: 'number',
            };
        },
    }));

})(Vue, VueForm);