import { locator } from 'raw/templating';
import Vue from 'vue';
import $ from 'jquery';
import { isFunction } from 'raw/std';
import Chart from 'chart.js';

const colors = {
    gray: {
        backgroundColor: 'rgba(210, 214, 222, 0.9)',
        borderColor: 'rgba(210, 214, 222, 0.9)',
        fillColor: 'rgba(210, 214, 222, 0.9)',
        strokeColor: 'rgba(210, 214, 222, 0.9)',
        pointColor: 'rgba(210, 214, 222, 0.9)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,0.9)',
    },
    red: {
        backgroundColor: 'rgba(221, 75, 57, 0.9)',
        borderColor: 'rgba(221, 75, 57, 0.9)',
        fillColor: 'rgba(221, 75, 57, 0.9)',
        strokeColor: 'rgba(221, 75, 57, 0.9)',
        pointColor: 'rgba(221, 75, 57, 0.9)',
        pointStrokeColor: '#dd4b39',
        pointHighlightFill: '#C43220',
        pointHighlightStroke: '#C43220',
    },
    blue: {
        backgroundColor: 'rgba(0, 115, 183, 0.9)',
        borderColor: 'rgba(0, 115, 183, 0.9)',
        fillColor: 'rgba(0, 115, 183, 0.9)',
        strokeColor: 'rgba(0, 115, 183, 0.9)',
        pointColor: 'rgba(0, 115, 183, 0.9)',
        pointStrokeColor: '#0073b7',
        pointHighlightFill: '#005A9E',
        pointHighlightStroke: '#005A9E',
    },
};


    let Component = Vue.component('chart', {
        template: locator.locate('chart'),

        props: {
            type: {
                default: 'line',
                validator: function(value) {
                    var possibleValues = ['line', 'bar', 'doughnut', 'area'];
                    return possibleValues.indexOf(value) >= 0;
                }
            },
            labels: {
                default: [],
            },
            datasets: {
                default: [],
            },
            options: {
                default: {},
            },
        },
        watch: {
            selectedType: function(type) {
                if(type === 'line') {
                    this.options.datasetFill = false;
                } else if(type === 'area') {
                    this.options.datasetFill = true;
                }
                this.redraw();
                var comp = this;
                this.$emit('change', {
                    type: comp.selectedType,
                    options: comp.options,
                    datasets: comp.datasets,
                });
            },
            labels: {
                deep: true,
                handler: function(newValue) {
                    this.reload();
                },
            },
        },
        methods: {
            redraw: function()
            {
                this.draw(this.selectedType, {
                    labels: this.labels,
                    datasets: this.datasets,
                }, this.options);
            },
            reloadData: function() {
                var comp = this;
                return new Promise((resolve, reject) => {

                    var promises = [];

                    if(isFunction(this.labels)) {
                        let resolver;
                        var promise = new Promise((res, rej) => {
                            resolver = res;
                        });
                        promise.then(function(res){
                            comp.labels = res;
                        });
                        this.labels(resolver);
                        promises.push(promise);
                    }


                    comp.datasets.forEach(function(dataset){
                        if(isFunction(dataset.dataCallback)) {

                            let resolver;

                            var promise = new Promise((res, rej) => {
                                resolver = res;
                            });
                            promise.then(function(data){
                                dataset.data = data;
                            });
                            dataset.dataCallback(resolver);
                            promises.push(promise);
                        }
                    });

                    Promise.all(promises).then(function(){
                        resolve();
                    });

                });

            },
            reload: function()
            {
                var comp = this;
                this.reloadData().then(function(){
                    comp.redraw();
                });
            },
            draw: function(type, data, options)
            {
                var $elm = $(this.$el);

                if(this.chart) {
                    this.chart.destroy();
                    this.chart = undefined;
                }

                var legendWidth = 200;
                var width = $elm.width() - legendWidth;

                //$elm.find('chart-canvas').html('');
                //var $canvas = $('<canvas></canvas>').attr('width', width).css('width', width+'px');
                //$elm.find('chart-canvas').append($canvas);
                var $canvas = $elm.find('canvas');

                var $legend = $elm.find('[data-chart-legend]');
                var ctx = $canvas.get(0).getContext('2d');
                ctx.canvas.width = width;


                //$canvas.attr('width', width).css('width', width+'px');


                var availableColors = Object.keys(colors);
                data.datasets.forEach(function(dataset){
                    if(!dataset.color) {
                        if(availableColors.length === 0) {
                            availableColors = Object.keys(colors);
                        }
                        dataset.color = availableColors.pop();
                    }
                    var colorSettings = colors[dataset.color];
                    for(var k in colorSettings) {
                        dataset[k] = colorSettings[k];
                    }
                });

                function createChart(type, ctx, config)
                {
                    switch(type) {
                        case 'line':
                            config.options.datasetFill = false;
                            return Chart.Line(ctx, config);
                            break;
                        case 'doughnut':
                            return Chart.Doughnut(ctx, config);
                            break;
                        case 'bar':
                            return Chart.Bar(ctx, config);
                            break;
                        case 'area':
                            config.options.datasetFill = true;
                            return Chart.Line(ctx, config);
                            break;
                        default:
                            throw 'Invalid type ' + type
                    }
                }

                var config = {
                    data: data,
                    options: options,
                };

                this.chart = createChart(type, ctx, config);

                var legend = this.chart.generateLegend();
                $legend.html(legend);


            }
        },
        data: function(){

            let selectedType = this.type;

            return {
                selectedType: selectedType,
            };
        },
        mounted: function(){

            var comp = this;

            var $elm = $(this.$el);

            var l = new ResizeListener($elm);

            l.then(function(dimensions){
                comp.redraw();
            });


            this.reload();
        },
    });

    function ResizeListener($elm)
    {
        var delay = 100;
        var oldWidth = $elm.width();
        var oldHeight = $elm.height();

        var cb = function(){};

        this.then = function(callback)
        {
            cb = callback;
        }

        var interval = setInterval(function(){

            if($elm.length == 0) {
                clearInterval(interval);
                return;
            }

            var newWidth = $elm.width();
            var newHeight = $elm.height();

            var changed = false;

            if(newWidth !== oldWidth) {
                changed = true;
                oldWidth = newWidth;
            }
            if(newHeight !== oldHeight) {
                changed = true;
                oldHeight = newHeight;
            }
            if(!changed) {
                return;
            }

            cb({
                width: newWidth,
                height: newHeight,
            });



        }, delay);
    };

export default Component;