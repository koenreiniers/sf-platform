import { locator } from 'raw/templating';
import Vue from 'vue';

let ActionComponent = Vue.extend({
    template: locator.locate('grid/action'),
    props: ['action'],
    data: function(){
        return {};
    },
    methods: {
        execute: function() {
            this.$emit('execute');
        },
    },
});


//let ActionComponent = {
//    template: locator.locate('grid/action'),
//    props: ['action'],
//    data: function(){
//        return {};
//    },
//    methods: {
//        execute: function() {
//            this.$emit('execute');
//        },
//    },
//};

export default ActionComponent;