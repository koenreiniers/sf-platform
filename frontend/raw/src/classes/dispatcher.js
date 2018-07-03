class Dispatcher {

    constructor()
    {
        this.listenersByEventName = {};
        this.bound = this;
    }

    getListeners(eventName)
    {
        var listenersByEventName = this.listenersByEventName;

        if(!listenersByEventName[eventName]) {
            listenersByEventName[eventName] = [];
        }
        return listenersByEventName[eventName];
    }

    bind(obj) {
        this.bound = obj;
    }

    trigger(eventName, event) {
        var args = [];
        for(var k in arguments) {
            var arg = arguments[k];
            args.push(arg);
        }
        args.shift();
        event = event || {};
        var dispatcher = this;
        this.getListeners(eventName).forEach(function(listener){
            listener.apply(dispatcher.bound, args);
        });
    }

    on(eventName, callback) {
        this.getListeners(eventName).push(callback);
        return this;
    }
}

export default Dispatcher;