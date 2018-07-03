import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
//import { Form as ParentComponent } from 'raw/components';
var ParentComponent = Vue.component('raw-form');

VueForm.type('checkbox', {
    component: 'raw-form-checkbox',
});

let Component = Vue.component('raw-form-checkbox', ParentComponent.extend({
    template: locator.locate('form/checkbox'),
    data: function(){
        return {};
    },
}));

export default Component;