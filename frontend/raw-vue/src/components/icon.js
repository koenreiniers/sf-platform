import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
var Component = {};
export default Component;

(function(Vue, VueForm){

    VueForm.type('icon', {
        component: 'raw-form-icon',
    });

    var icons = ['users', 'user-plus', 'bell', 'flag', 'circle', 'area-chart', 'gift', 'plus', 'ship'];

    var iconChoices = {};
    icons.forEach(function(icon){
        var label = '<i class="fa fa-'+icon+'"></i> '+icon;
        iconChoices[label] = icon;
    });

    Vue.component('raw-form-icon', Vue.component('raw-form-choice').extend({
        data: function(){

            this.choices = iconChoices;

            return {
                choices: this.choices,
            };
        },
    }));

})(Vue, VueForm);