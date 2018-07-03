import FilterComponent from './filter';
import { locator } from 'raw/templating';
import Vue from 'vue';

let Filterer = {
    template: locator.locate('grid/filterer'),
    props: ['definitions', 'filters', 'usersettings'],
    components: {
        'grid-filter': FilterComponent,
    },
    data: function() {

        var filters = this.filters;
        var definitions = this.definitions;
        var usersettings = this.usersettings;
        var actions = [];

        return {
            newFilters: [],
            actions: [],
        };
    },
    methods: {
        clearFilters: function() {
            if(this.filters.length > 0) {
                this.filters.splice(0, this.filters.length);
            }
            this.newFilters.splice(0, this.newFilters.length);
        },
        undo: function() {

            let lastAction = this.actions.pop();

            switch(lastAction.type) {
                case 'remove':
                    this.filters.push(lastAction.filter);
                    break;
                case 'add':
                    Vue.delete(this.filters, lastAction.index);
                    break;
            }
        },
        toggleMode: function() {
            this.usersettings.advancedMode = !this.usersettings.advancedMode;
        },
        removeFilter: function(index) {

            let filter = this.filters[index];

            Vue.delete(this.filters, index);

            this.actions.push({
                type: 'remove',
                filter: filter,
            });

        },
        addNewFilter: function(index) {

            var filter = this.newFilters[index];

            this.filters.push(filter);

            Vue.delete(this.newFilters, index);

            this.actions.push({
                type: 'add',
                filter: filter,
                index: this.filters.length-1,
            });

        },
        removeNewFilter: function(index) {
            Vue.delete(this.newFilters, index);
        },
        addFilter: function() {

            var name = Object.keys(this.definitions)[0];
            var definition = this.definitions[name];

            this.newFilters.push({
                name: null,
                operator: null,
                data: [''],
            });

        },
    },
};



export default Filterer;