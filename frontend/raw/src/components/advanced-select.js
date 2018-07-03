import { locator } from 'raw/templating';
import Vue from 'vue';
import $ from 'jquery';

var Component = Vue.component('advanced-select', {
    template: locator.locate('advanced_select'),
    props: {
        choices: {
            default: {},
        },
        value: {},
        required: {
            type: Boolean,
            default: true,
        },
        multiple: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        select: function(label) {
            this.searchTerm = null;
            this.filteredChoices = this.choices;
            this.activeLabel = label;
            this.newValue = this.choices[label];
            this.open = false;
            this.$emit('input', this.newValue);
        },
        close: function() {
            this.open = false;
        },
        toggle: function() {
            this.open = !this.open;
        },
        search: function(e) {
            this.filterChoices();
        },
        filterChoices: function()
        {
            var searchTerm = this.searchTerm;
            if(!searchTerm) {
                this.filteredChoices = this.choices;
                return;
            }
            var filtered = {};
            for(var k in this.choices) {
                var v = this.choices[k];
                if(k.indexOf(searchTerm) >= 0 || v.indexOf(searchTerm) >= 0) {
                    filtered[k] = v;
                }
            }
            this.filteredChoices = filtered;
        },
        update: function() {

            this.filterChoices();

            var activeLabel = null;
            if(this.newValue) {
                for(var k in this.choices) {
                    if(this.choices[k] === this.newValue) {
                        activeLabel = k;
                    }
                }
            }
            this.activeLabel = activeLabel;
        },
    },
    watch: {
        choices: function(newValue) {
            this.update();
        },
        searchTerm: function(newValue) {
            this.update();
        },
        value: function(value) {
            this.update();
        },
        open: function(open) {
            if(!open) {
                return false;
            }
            var $elm = $(this.$el);

            setTimeout(function(){
                var $input = $elm.find('.search-input');
                $input.focus();
            }, 5);


        }
    },
    data: function(){




        this.activeLabel = null;

        this.newValue = this.value;

        this.update();

        return {
            searchTerm: null,
            activeLabel: this.activeLabel,
            open: false,
        };
    },
});

export default Component;