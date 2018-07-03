import Vue from 'vue';
import { Raw, http, router } from 'raw';
import GridStorage from '../classes/storage';
import { locator } from 'raw/templating';

import Grid from 'raw-grid';
import { GridDataTypes, GridSources, GridFormatter } from 'raw-grid/extensions';

var storedProperties = ['filters', 'pagination', 'usersettings'];

function loadGridProperties(data, config)
{
    data.gridConfig = config;
    data.massActions = config.massActions;
    data.columns = config.columns;


    data.filterDefinitions = config.filters;



    data.source = GridSources.source(config.source.type);

    data.loading = false;

    data.initialized = true;

}
function loadName(data, name)
{
    data.gridName = name;
    data.storage = new GridStorage(name+'_grid_storage');
}

const componentName = 'grid';


var GridComponent = Vue.extend({
    template: locator.locate('grid'),
    props: {
        config: {
            default: null,
        },
        name: {
            default: null,
        },
        parameters: {
            default: {},
        },
    },
    props2: ['config', 'parameters', 'name'],
    watch: {
        name: function(newValue) {
            this.initialize(this);
        },
    },
    data: function() {

        var self = this;

        if(!this.config && !this.name) {
            throw 'Either the grids name or config must be set';
        }

        var data = {
            loading: true,
            items: [],
            usersettings: {
                advancedMode: false,
            },
            columns: {},
            initialized: false,
            filters: {},
            gridName: this.name,
            gridConfig: this.config,
        };

        this.initialize(data);

        return data;
    },
    beforeCreate: function() {
        var self = this;
        this.$on('initialized', function(){
            self.fetchItems();
        });
        this.$on('fetch', function(args, options){
            args.parameters = this.parameters;
        });
    },
    updated: function() {
        var storage = this.storage;
        for(var i = 0; i < storedProperties.length; i++) {
            var property = storedProperties[i];
            storage.set(property, this[property]);
        }
    },
    created: function() {
        var storage = this.storage;
        for(var i = 0; i < storedProperties.length; i++) {
            var property = storedProperties[i];
            if(storage.has(property)) {
                this[property] = storage.get(property);
            }
        }
    },
    methods: {
        initialize: function(data){
            var self = this;
            if(!data.gridConfig) {
                loadName(data, data.gridName);

                http.get(router.generate('raw_grid.grid.view', {
                    gridName: data.gridName,
                })).then(function(response){
                    var config = response.data;
                    loadGridProperties(self, config);
                    self.$emit('initialized');
                });
            } else {
                var config = data.gridConfig;
                loadName(data, config.gridName);
                loadGridProperties(data, config);
                self.$emit('initialized');
            }
        },
        getInputType: function(type) {
            return GridDataTypes.dataType(type).inputType;
        },
        displayData: function(item, column) {
            var value = item[column.property];
            if(column.formatter !== null) {
                value = GridFormatter.get(column.formatter).format(value);
            }
            return value;
        },
        fetchItems: function() {
            if(!this.initialized) {
                return;
            }

            this.loading = true;
            var self = this;

            var args = {};

            this.$emit('fetch', args, this.gridConfig.source);


            var result = this.source.getItems(this, this.gridConfig.source, args);
            if(this.source.async) {
                this.loading = true;
                result.then(function(items){
                    self.items = items;
                    self.loading = false;
                });
            } else {
                this.items = result;
            }

        },
    },
});



Vue.component(componentName, GridComponent);

Grid.initialize();

export default GridComponent;