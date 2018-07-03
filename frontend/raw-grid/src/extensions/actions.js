

function Action(type)
{
    this.config = {
        execute: function(grid, options, item){},
        build: function(action) {},
    };
    this.type = type;


    this.configure = function(config)
    {
        if(config === undefined) {
            return this.config;
        }
        for(var k in config) {
            this.config[k] = config[k];
        }
        return this;
    }

    this.extend = function(config) {
        var self = this;
        var merged = {};
        merged.build = function(action) {
            self.config.build(action);
            config.build(action);
        }
        merged.execute = function(grid, action, item) {
            self.config.execute(grid, action, item);
            config.execute(grid, action, item);
        }
        return merged;
    };

    this.build = function(action)
    {
        this.config.build.call(action, action);
    }

    this.execute = function(grid, options, item)
    {
        this.config.execute.call(grid, options, item, grid);
    }

};

function GridActions()
{

}

let actions = {};

GridActions.action = function(type, config)
{
    var action = actions[type];
    if(config === undefined) {
        if(!action) {
            throw 'Action ' + type + ' does not exist';
        }
        return action;
    }
    if(!action) {
        action = new Action(type);
        actions[type] = action;
    }
    action.configure(config);
    return this;
};



export default GridActions;
