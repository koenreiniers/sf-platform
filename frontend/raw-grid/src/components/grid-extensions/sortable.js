import Grid from '../../classes/grid';

Grid.extend(function(){
    Grid.extendComponent({
        data: function(){
            return {
                sort: {
                    by: null,
                    dir: null,
                },
            };
        },
        beforeCreate: function() {
            this.$on('fetch', function(args, options){
                args.sort = this.sort;
            });
        },
        watch: {
            sort: {
                deep: true,
                handler: function(newValue, oldValue) {
                    this.fetchItems();
                }
            },
        },
        methods: {
            sortBy: function(columnName) {

                var column = this.columns[columnName];

                if(!column.sortable) {
                    return;
                }
                var dir = 'ASC';
                if(this.sort.by === columnName) {
                    dir = this.sort.dir === 'ASC' ? 'DESC' : 'ASC';
                }
                this.sort.by = columnName;
                this.sort.dir = dir;
            },
        },
    });
});