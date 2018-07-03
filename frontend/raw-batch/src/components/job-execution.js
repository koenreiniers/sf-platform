import { locator } from 'raw/templating';
import { http, router } from 'raw';
import Vue from 'vue';

// TODO: Add refresh interval

var Component = Vue.component('job-execution', {
    template: locator.locate('batch/job-execution'),
    props: {
        id: {
            type: Number,
        },
    },
    data: function() {


        var jobExecution = {
            stepExecutions: [
            ],
        };

        this.refresh();

        return {
            refreshing: true,
            jobExecution: jobExecution,
        };
    },
    destroyed: function() {

    },
    methods: {
        refresh: function() {

            this.refreshing = true;

            var refreshInterval = 500;



            var url = router.generate('raw_batch.job_execution.view', {
                id: this.id,
            });

            var comp = this;

            http.get(url).then(function(response) {
                comp.jobExecution = response.data;

                if(comp.jobExecution.status !== 'completed' && comp.jobExecution.status !== 'failed') {

                    var timeout = setTimeout(() => {
                        comp.refresh();
                    }, refreshInterval);
                }

                comp.refreshing = false;



            });
        },
    },
});

export default Component;