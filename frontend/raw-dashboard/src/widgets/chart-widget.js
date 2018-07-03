import { locator } from 'raw/templating';
import { http, router } from 'raw';
import { humanize } from 'raw/std';
import Vue from 'vue';


    var types = ['line', 'area', 'bar'];

    function createChoices(items)
    {
        var choices = {};
        for(var k in items) {

            var value = items[k];
            var label = humanize(value);

            choices[label] = value;
        }
        return choices;
    }

    var settingsForm = {
        datasetNames: {
            type: 'choice',
            multiple: true,
            choices: {},
        },
    };





    http.get(router.generate('raw_dashboard.statistic.dataset.index')).then(function(response){

        var datasets = response.data;

        for(var name in datasets) {
            var dataset = datasets[name];
            var label = humanize(dataset.label);
            settingsForm.datasetNames.choices[label] = name;
        }
    });

    let Widget = Raw.widget('chart', {
        label: 'Chart',
        icon: 'area-chart',
        description: 'This is a chart',
        component: 'raw-chart-widget',
        settingsForm: settingsForm,
    });

    function resolveParameters(params)
    {
        var resolved = {};
        for(var k in params) {
            if(params[k] === undefined) {
                continue;
            }
            resolved[k] = params[k];
        }
        return resolved;
    }

    Vue.component('raw-chart-widget', {
        props: {
            widget: {
                required: true,
            },
            dashboard: {
                required: true,
            },
        },
        template: locator.locate('widgets/chart'),
        methods: {
            onChartChange: function(e) {
                this.widget.settings.type = e.type;
            },
            update: function()
            {
                var comp = this;
                var widget = comp.widget;
                var datasetNames = this.widget.settings.datasetNames;
                var parameters = resolveParameters({
                    start: widget.settings.start,
                    end: widget.settings.end,
                });

                http.get(router.generate('raw_dashboard.statistic.dataset.view', {
                    name: datasetNames[0],
                    parameters: parameters,
                })).then(function(response){
                    var labels = Object.keys(response.data);
                    comp.labels = labels;
                });
            },
            createDatasets: function(){
                var widget = this.widget;
                var datasetNames = widget.settings.datasetNames;
                var datasets = [];
                datasetNames.forEach(function(datasetName){
                    datasets.push({
                        label: humanize(datasetName),
                        dataCallback: function(resolve) {

                            var parameters = resolveParameters({
                                start: widget.settings.start,
                                end: widget.settings.end,
                            });

                            http.get(router.generate('raw_dashboard.statistic.dataset.view', {
                                name: datasetName,
                                parameters: parameters,
                            })).then(function(response){

                                var data = [];
                                for(var k in response.data) {
                                    var val = response.data[k];
                                    data.push(val);
                                }
                                resolve(data);
                            });
                        },
                    });
                });
                return datasets;
            },
        },
        watch: {
            'widget.width': function(width){
                this.$emit('save', this.widget);
                //this.update();
            },
            'widget.settings.datasetNames': {
                deep: true,
                handler: function(newValue, oldValue) {
                    this.datasets = this.createDatasets();
                    this.update();
                },
            },
            'widget.settings.parameters': {
                deep: true,
                handler: function(newValue, oldValue) {
                    this.update();
                }
            },
            'widget.settings.type': function(){
                //this.update();
                this.$emit('save', this.widget);
            },
            'dashboard.dateRange': {
                deep: true,
                handler: function(dateRange) {
                    this.widget.settings.start = dateRange.start;
                    this.widget.settings.end = dateRange.end;
                    this.update();
                    this.$emit('save', this.widget);
                },
            },
            //'dateRange': {
            //    deep: true,
            //    handler: function(dateRange) {
            //        this.widget.settings.start = dateRange.start;
            //        this.widget.settings.end = dateRange.end;
            //        this.update();
            //        this.$emit('save', this.widget);
            //    },
            //},
        },
        data: function() {

            var widget = this.widget;

            var datasetNames = this.widget.settings.datasetNames;

            var datasets = this.createDatasets();

            this.update();


            var options = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,0.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 1,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: false,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){ %><li><span class=\"label\" style=\"background-color:<%=datasets[i].fillColor%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></span></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };

            return {
                datasets: datasets,
                options: options,
                labels: [],
            };
        },
    });

export default Widget;