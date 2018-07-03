import ExtensibleObject from '../classes/extensible-object';


function GridOperators()
{

}
let operators = {};
GridOperators.operator = function(name, config)
{
    if(config === undefined) {
        return operators[name];
    }
    var resolved = {
        label: name,
        args: 1,
    };
    for(var k in config) {
        resolved[k] = config[k];
    }
    operators[name] = new ExtensibleObject(name, resolved);
    return operators[name];
};


export default GridOperators;