export default {};
import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';
import { humanize } from 'raw/std';

(function(Vue, Raw){


    var settingsForm = {
        statisticName: {
            type: 'choice',
            choices: function(resolve, reject){
                http.get(router.generate('raw_dashboard.statistic.index')).then(function(response){
                    var choices = {};
                    for(var name in response.data) {
                        var statistic = response.data[name];
                        var label = humanize(name);
                        choices[label] = name;
                    }
                    resolve(choices);
                });
            },
        },
        color: {
            type: 'color',
        },
        icon: {
            type: 'icon',
        },
    };

    Raw.widget('info_box', {
        label: 'Info box',
        description: 'This is an info box',
        component: 'raw-info-box-widget',
        settingsForm: settingsForm,
        width: 3,
        height: 2,
        resizable: false,
    });



    Vue.component('raw-info-box-widget', {
        props: {
            widget: {
                required: true,
            },
            dashboard: {
                required: true,
            },
        },
        template: locator.locate('widgets/info_box'),
        watch: {
            'widget.settings.statisticName': function(name) {
                if(!name) {
                    return;
                }
                this.fetchData(name);
            },
            'dashboard.dateRange': {
                deep: true,
                handler: function(dateRange) {
                    this.update();
                },
            },
        },
        methods: {
            update: function()
            {
                var widget = this.widget;

                if(widget.settings.statisticName) {
                    this.fetchData(widget.settings.statisticName);
                }
            },
            fetchData: function(name)
            {
                this.title = humanize(name);
                var data = {
                    name: name,
                    start: this.dashboard.dateRange.start,
                    end: this.dashboard.dateRange.end,
                };
                var comp = this;
                http.get(router.generate('raw_dashboard.statistic.metric.view', data)).then(function(response){
                    comp.previousData = response.data.previousData;
                    comp.currentData = response.data.currentData;
                    comp.progress = response.data.progress;
                    comp.datas = response.data;
                });
            },
        },
        data: function() {

            this.title = 'Info box';

            this.update();




            return {
                currentData: 15,
                previousData: 10,
                progress: 0,
                datas: null,
            };
        },
    });

})(Vue, Raw);