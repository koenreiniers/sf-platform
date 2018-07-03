import { locator } from 'raw/templating';
import Vue from 'vue';

var Component = Vue.component('dropdown', {
    template: locator.locate('dropdown'),
    props: {

    },
});

export default Component;