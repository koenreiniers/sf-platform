// @var Raw
import Vue from 'vue';
import VueForm from '../form';
import { locator } from 'raw/templating';
import { humanize } from 'raw/std';
//import { Choice as ParentComponent } from 'raw/components';
var ParentComponent = Vue.component('raw-form-choice');

VueForm.type('color', {
    component: 'raw-form-color',
});



function getColorChoices()
{
    var colors = Raw.colorNames();
    var colorChoices = {};
    colors.forEach(function(color){
        var label = '<span class="label bg-'+color+'"> </span>&nbsp;'+humanize(color);
        colorChoices[label] = color;
    });
    return colorChoices;
}

let Component = Vue.component('raw-form-color', ParentComponent.extend({
    data: function(){

        var colorChoices = getColorChoices();

        this.choices = colorChoices;

        return {
            choices: this.choices,
        };
    },
}));

export default Component;