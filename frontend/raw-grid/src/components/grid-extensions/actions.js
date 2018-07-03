import { GridActions } from 'raw-grid/extensions';
import ActionComponent from '../action';
import Grid from '../../classes/grid';

Grid.extend(function(){

    Grid.extendComponent({
        components: {
            'action': ActionComponent,
        },
    });

    Grid.extendComponent({
        data: function() {
            return {
                actions: {},
            };
        },
        methods: {
            executeAction: function(action, item) {
                GridActions.action(action.type).execute(this, action, item);
            },
            hasActions: function() {
                return Object.keys(this.actions).length > 0;
            },
        },
        beforeCreate: function() {
            var grid = this;
            this.$on('initialized', function(){
                var actions = grid.gridConfig.actions;
                for(var k in actions) {
                    if(!actions.hasOwnProperty(k)) {
                        continue;
                    }
                    var action = actions[k];
                    GridActions.action(action.type).build(action);
                    grid.actions[k] = action;
                }
            });
        },
    });
});