import Vue from 'vue';
import { locator } from 'raw/templating';
import { Flash } from 'raw/misc';

let Component = Vue.extend({
    template: locator.locate('flash_messages'),
    props: {
        flashes: {},
    },
    data: function() {

        var flashes = this.flashes;

        Flash.on('flash', function(flash){

            flashes.push({
                level: flash.level,
                message: flash.message,
            });

        });

        return {};
    },

    methods: {
        dismiss: function(flash, index) {
            Vue.delete(this.flashes, index);
        }
    },
});

Vue.component('flash-messages', Component);

export default Component;