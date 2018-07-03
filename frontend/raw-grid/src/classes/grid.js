import Vue from 'vue';

function Grid()
{

}

Grid.componentName = null;
Grid.setComponentName = function(componentName)
{
    Grid.componentName = componentName;
};
Grid.extendComponent = function(extend) {
    var newComp = Vue.component(Grid.componentName, Vue.component(Grid.componentName).extend(extend));
    return newComp;
};

let extensions = [];
let configurers = [];
Grid.configure = function(callback)
{
    configurers.push(callback);
    return this;
};
Grid.extend = function(callback) {
    extensions.push(callback);
    return this;
};
Grid.initialize = function()
{
    const componentName = 'grid';

    Grid.setComponentName(componentName);

    extensions.forEach(function(callback){
        callback();
    });

    configurers.forEach(function(callback){
        callback();
    });
};

export default Grid;