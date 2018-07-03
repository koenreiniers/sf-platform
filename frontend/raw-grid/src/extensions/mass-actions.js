function MassAction(type)
{
    this.config = {
        async: false,
        execute: function(){},
    };
    this.type = type;

    this.extend = function(config)
    {
        if(config === undefined) {
            return this.config;
        }
        for(var k in config) {
            this.config[k] = config[k];
            //this[k] = config[k];
        }
        return this;
    }

    this.execute = function(grid, options, ids, items, resolve, reject)
    {
        return this.config.execute.call(grid, options, ids, items, resolve, reject);
    }

};

function GridMassActions()
{

}
let actions = {};
GridMassActions.massAction = function(type, config)
{
    if(config === undefined) {
        return actions[type];
    }
    var action = actions[type];
    if(!action) {
        action = new MassAction(type);
        actions[type] = action;
    }
    action.extend(config);
    return this;
}

export default GridMassActions;