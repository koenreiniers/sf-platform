// @var Grid
import Vue from 'vue';
import { locator } from 'raw/templating';
import { GridOperators, GridDataTypes } from 'raw-grid/extensions';

function createFilterComponent()
{
    function mergeFilter(target, source)
    {
        target.name = source.name;
        target.operator = source.operator;
        target.data.length = 0;
        for(var i = 0; i < source.data.length; i++) {
            target.data.push(source.data[i]);
        }
    }

    function copyFilter(filter)
    {
        var copy = {
            name: filter.name,
            operator: filter.operator,
            data: [],
        };
        mergeFilter(copy, filter);
        return copy;
    }
    function validateFilter(filter) {
        if(filter.name === null || filter.operator === null) {
            return false;
        }
        for(var i = 0; i < filter.data.length; i++) {
            var value = filter.data[i];
            if(value == '' || value == null) {
                return false;
            }
        }
        return true;
    }
    function getAvailableOperators(filter, definitions) {
        var dataType = getDataType(filter, definitions);
        if(!dataType) {
            return null;
        }
        var operators = {};
        for(var k in dataType.operators) {
            var opname = dataType.operators[k];
            operators[opname] = GridOperators.operator(opname);
        }
        return operators;
    }

    function getDefaultOperator(filter, definitions)
    {
        var dataType = getDataType(filter, definitions);
        if(!dataType) {
            return null;
        }
        return dataType.defaultOperator;
    }

    function getFilterDefinition(filter, definitions)
    {
        return definitions[filter.name];
    }

    function getDataType(filter, definitions)
    {
        var definition = getFilterDefinition(filter, definitions);
        if(!definition) {
            return null;
        }
        return GridDataTypes.dataType(definition.type);
    }

    function getInputType(filter, definitions)
    {
        var dataType = getDataType(filter, definitions);
        if(!dataType) {
            return null;
        }
        return dataType.inputType;
    }
    function getSelectedOperator(filter)
    {
        if(!filter.operator) {
            return null;
        }
        return GridOperators.operator(filter.operator);
    }

    var Filter = Vue.extend({
        template: locator.locate('grid/filter'),
        props: ['filter', 'definitions', 'usersettings'],
        watch: {
            editableFilter: {
                deep: true,
                handler: function(newValue, oldValue) {

                    var filter = newValue;

                    this.filterDefinition = getFilterDefinition(filter, this.definitions);
                    this.inputType = getInputType(filter, this.definitions);
                    this.valid = validateFilter(filter);
                    this.selectedOperator = getSelectedOperator(filter);
                },
            },
            'editableFilter.name': function(newValue, oldValue) {

                var filter = this.editableFilter;
                var operator = filter.operator;
                this.availableOperators = getAvailableOperators(filter, this.definitions);

                if(operator === null || !this.usersettings.advancedMode) {
                    filter.operator = getDefaultOperator(filter, this.definitions);
                }
            },
            'editableFilter.operator': function(newValue, oldValue) {

                var operator = GridOperators.operator(newValue);

                if(!operator) {
                    return;
                }

                var filter = this.editableFilter;

                while(filter.data.length < operator.args) {
                    filter.data.push('');
                }
                if(filter.data.length > operator.args) {
                    filter.data.length = operator.args;
                }

            },
        },
        methods: {
            displayData: function(data) {
                var dataType = this.filterDefinition.type;
                var formatted = data.map(function(val){
                    return GridDataTypes.dataType(dataType).format(val);
                });
                return formatted.join(' and ');
            },
            save: function() {
                if(!this.valid) {
                    return;
                }
                mergeFilter(this.filter, this.editableFilter);
                this.editing = false;
                this.$emit('save', this.filter);
            },
            cancel: function() {
                mergeFilter(this.editableFilter, this.filter);

                var valid = validateFilter(this.filter);

                this.editing = false;

                if(!valid) {
                    this.remove();
                }
            },
            edit: function() {
                this.editing = true;
            },
            remove: function() {
                this.$emit('remove', this.filter);
            }
        },
        data: function() {

            var filter = this.filter;


            var filterDefinitions = this.definitions;


            var editableFilter = copyFilter(filter);

            var data = {

                editableFilter: editableFilter,
                filterDefinition: getFilterDefinition(filter, filterDefinitions),
                selectedOperator: getSelectedOperator(filter),
                inputType: getInputType(filter, filterDefinitions),
                availableOperators: getAvailableOperators(filter, filterDefinitions),
                valid: validateFilter(filter),
                editing: filter.name === null,
            };

            return data;


        },
    });
    return Filter;
};

let Filter = createFilterComponent();

export default Filter;