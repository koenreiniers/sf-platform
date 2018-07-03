import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
import { isFunction } from 'raw/std';
//import { Form as ParentComponent } from 'raw/components';
var ParentComponent = Vue.component('raw-form');


VueForm.type('choice', {
    component: 'raw-form-choice',
});

let Component = Vue.component('raw-form-choice', ParentComponent.extend({
    template: locator.locate('form/select'),
    data: function(){

        var choices = {};

        var comp = this;

        if(isFunction(this.config.choices)) {

            var resolve = function(result) {
                comp.choices = result;
            };
            var reject = function(msg) {
                throw msg;
            }

            this.config.choices(resolve, reject);
        } else {
            choices = this.config.choices;
        }

        if(choices instanceof Promise) {
            choices.then(function(val){
                comp.choices = val;
            });
            choices = {};
        }

        var multiple = this.config.multiple;

        return {
            choices: choices,
            multiple: multiple,
        };
    },
}));

export default Component;