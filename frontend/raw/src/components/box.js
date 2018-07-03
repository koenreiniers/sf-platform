import { locator } from 'raw/templating';
import Vue from 'vue';

var Component = Vue.component('box', {
    template: locator.locate('box'),
    props: {
        solid: {
            type: Boolean,
            default: false,
        },
        type: {
            default: 'default'
        },
    },
});

export default Component;