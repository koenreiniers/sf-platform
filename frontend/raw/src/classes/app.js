class App {
    constructor(config)
    {
        this.params = config;
    }

    config(config) {
        var params = this.params;
        if(config === undefined) {
            return params;
        }
        for(var k in config) {
            params[k] = config[k];
        }
        return this;
    }

    param(name, value) {
        var params = this.params;
        if(value === undefined) {
            if(!this.hasParam(name)) {
                throw 'Parameter "'+name+'" is not set';
            }
            return params[name];
        }
        params[name] = value;
        return this;
    }

    hasParam(name) {
        return this.params.hasOwnProperty(name);
    }

}

export default App;