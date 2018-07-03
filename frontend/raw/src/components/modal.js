import Vue from 'vue';
import { locator } from 'raw/templating';
import $ from 'jquery';


var Component = Vue.component('modal', {
    template: locator.locate('modal'),
    props: ['show'],

    watch: {
        show: function(newValue, oldValue) {
            this.update();
            if(newValue === true) {
                this.$emit('show');
            } else {
                this.$emit('hide');
            }
        },
    },
    mounted: function()
    {
        var $modal = $(this.$el);
        var comp = this;
        $modal.on('shown.bs.modal', function(){
            comp.$emit('show');
            //comp.show = true;
        });
        $modal.on('hidden.bs.modal', function(){
            comp.$emit('hide');
            //comp.show = false;
        });
        this.update();
    },
    methods: {
        close: function(){
            this.$emit('hide');
            //this.show = false;
        },
        update: function(){
            var $modal = $(this.$el);
            if(this.show === true) {
                $modal.modal('show');
            } else {
                $modal.modal('hide');
            }
        },
    },
});

export default Component;
