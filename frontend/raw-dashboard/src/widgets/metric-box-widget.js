export default {};
import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';
import { humanize } from 'raw/std';

(function(Vue, Raw){

    return;

    var colors = ['blue', 'green', 'yellow', 'red'];
    var icons = ['users', 'flag', 'circle'];

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

    var iconChoices = {};
    icons.forEach(function(icon){
        var label = '<i class="fa fa-'+icon+'"></i> '+icon;
        iconChoices[label] = icon;
    });

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
            type: 'choice',
            choices: createChoices(colors),
        },
        icon: {
            type: 'choice',
            choices: iconChoices,
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

    Vue.component('raw-metric-box-widget', {
        props: ['widget'],
        template: locator.locate('widgets/info_box'),
        watch: {
            'widget.settings.statisticName': function(statisticName) {
                if(!statisticName) {
                    return;
                }
                this.fetchStatistic(statisticName);
            },
        },
        methods: {
            fetchStatistic: function(statisticName)
            {
                this.title = humanize(statisticName);

                var comp = this;
                http.get(router.generate('raw_dashboard.statistic.view', {
                    name: statisticName,
                })).then(function(response){
                    comp.statistic = response.data;
                });
            },
        },
        data: function() {

            var widget = this.widget;

            this.title = 'Info box';

            if(widget.settings.statisticName) {
                this.fetchStatistic(widget.settings.statisticName);

            }


            return {
                statistic: null,
                widget: widget,
            };
        },
    });

})(Vue, Raw);