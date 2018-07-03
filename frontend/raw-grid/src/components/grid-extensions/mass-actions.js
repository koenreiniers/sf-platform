import { Raw } from 'raw';
import Grid from '../../classes/grid';
import { GridMassActions } from 'raw-grid/extensions';

Grid.extend(function(){
    Grid.extendComponent({
        data: function(){
            return {
                massActions: [],
            };
        },
        methods: {
            executeMassAction: function(massAction, items) {

                if(items.length === 0) {
                    return;
                }

                var identifierProperty = this.gridConfig.identifier;

                var ids = [];
                items.forEach(function(item){
                    ids.push(item[identifierProperty]);
                });


                var confirmed = confirm('Are you sure you want to perform action ' + massAction.type + ' on ' + items.length + ' items?');

                if(!confirmed) {
                    return;
                }
                var massActionType = GridMassActions.massAction(massAction.type);

                var resolver, rejector;
                var promise = new Promise((resolve, reject) => {

                });
                promise.then(function(){
                    flash.flash('success', 'Mass action has been executed');
                });

                massActionType.execute(this, massAction, ids, items, resolver, rejector);


            },
            hasMassActions: function() {
                if(!this.massActions) {
                    return false;
                }
                return Object.keys(this.massActions).length > 0;
            },
        },
    });
});