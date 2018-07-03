function uniqueId(len)
{
    len = len || 12;
    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for( var i= 0; i < len; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
    return makeid();
};

function humanize(text)
{
    if(!text) {
        return '';
    }
    return text
        .replace(/_/g, ' ')
        .trim()
        .replace(/\b[A-Z][a-z]+\b/g, function(word) {
            return word.toLowerCase()
        })
        .replace(/^[a-z]/g, function(first) {
            return first.toUpperCase()
        })
}

function isFunction(value)
{
    return value && Object.prototype.toString.call(value) === '[object Function]';
}

function isArray(object)
{
    return Object.prototype.toString.call( object ) === '[object Array]';
}
function isObject(object)
{
    return object !== null && typeof object === 'object';
}

function merge(target, source)
{
    var copy = deepCopy(source);
    for(var k in copy) {
        target[k] = copy[k];
    }
}
function deepCopy(object) {
    if(!isObject(object)) {

        if(object === false) {

        }
        return object;
    } else if(isArray(object)) {
        var copy = [];
        for(var i = 0; i < object.length; i++) {
            copy[i] = deepCopy(object[i]);
        }
        return copy;
    }
    var copy = {};
    for(var k in object) {
        copy[k] = deepCopy(object[k]);
    }
    return copy;
}

function apply(Raw)
{
    Raw.uniqueId = uniqueId;

    Raw.humanize = humanize;

    Raw.isFunction = isFunction;

    Raw.isArray = isArray;

    Raw.isObject = isObject;

    Raw.merge = merge;

    Raw.deepCopy = deepCopy;
};

export {
    uniqueId, humanize, isFunction, isArray, isObject, merge, deepCopy
};

var all = {};

apply(all);

export default all;