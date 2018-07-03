import Grid from '../../classes/grid';
import FiltererComponent from '../filterer';

Grid.extend(function(){
    Grid.extendComponent({
        components: {
            'filterer': FiltererComponent,
        },
    });

    Grid.extendComponent({
        data: function() {

            var filters = [];

            return {
                filterDefinitions: {},
                filters: filters,
            };
        },
        beforeCreate: function() {



            this.$on('fetch', function(args, options){
                args.filters = this.filters;
            });
        },
        watch: {
            filters: {
                deep: true,
                handler: function(newValue, oldValue) {
                    this.fetchItems();
                },
            },
        },
        methods: {
            canFilter: function() {
                return true;
                return Object.keys(this.filters).length > 0;
            },
        },
    });
});