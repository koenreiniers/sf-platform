import { locator } from 'raw/templating';
import Vue from 'vue';
import $ from 'jquery';




let Component = Vue.component('vector-map', {
    template: locator.locate('vector_map'),
    props: ['values'],
    methods: {

    },
    watch: {

    },
    mounted: function()
    {
        var comp = this;

        $(this.$el).vectorMap({
            map: 'world_mill_en',
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: '#e4e4e4',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0,
                    "stroke-opacity": 1
                }
            },
            series: {
                regions: [{
                    values: this.values,
                    scale: ["#92c1dc", "#ebf4f9"],
                    normalizeFunction: 'polynomial'
                }]
            },
            onRegionLabelShow: function (e, el, code) {
                if (typeof comp.values[code] != "undefined") {
                    el.html(el.html() + ': ' + comp.values[code] + ' new visitors');
                }

            }
        });
    },
    data: function(){



        return {};
    },
});

export default Component;