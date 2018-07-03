import $ from 'jquery';
import { locator } from 'raw/templating';
import moment from 'moment';
import Vue from 'vue';


const ranges = {
    'Today': [moment().set(), moment()],
    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'This Month': [moment().startOf('month'), moment().endOf('month')],
    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'This year': [moment().startOf('year'), moment().endOf('year')],
    'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
};
const displayFormat = 'DD-MM-YYYY';

let Component = Vue.component('date-range-picker', {
    template: locator.locate('date_range_picker'),
    props: ['value'],
    methods: {
        renderLabel: function()
        {
            var $picker = $(this.$el);
            var comp = this;
            if(!this.value) {
                return;
            }

            if(!comp.value.start || !comp.value.end) {
                return;
            }

            var label = comp.value.start + ' - ' + comp.value.end;
            var startFormat = comp.value.start.substr(0, 10);
            var endFormat = comp.value.end.substr(0, 10);

            for(var k in ranges) {
                var range = ranges[k];
                if(startFormat === range[0].format(displayFormat) && endFormat === range[1].format(displayFormat)) {
                    label = k;
                    break;
                }
            }

            $picker.find('span').html(label);
        },
        update: function() {


        },
    },
    watch: {
        value: {
            deep: true,
            handler: function(value) {
                this.renderLabel();
            },
        },
    },
    mounted: function() {


        var $picker = $(this.$el);
        var comp = this;

        var dateFormat = 'DD-MM-YYYY HH:mm:ss';

        if(!comp.value) {
            comp.value = {};
        }
        if(!comp.value.start) {
            comp.value.start = moment().subtract(29, 'days').format(dateFormat);
        }
        if(!comp.value.end) {
            comp.value.end = moment().format(dateFormat);
        }
        this.renderLabel();

        $picker.daterangepicker(
            {
                ranges: ranges,
                startDate: moment(comp.value.start),
                endDate: moment(comp.value.end)
            },
            function (start, end) {

                comp.value.start = start.format(dateFormat);
                comp.value.end = end.format(dateFormat);
                comp.$emit('input', comp.value);


            }
        );
    },
    data: function(){

        this.update();
        this.renderLabel();

        return {

        };
    },
});

export default Component;