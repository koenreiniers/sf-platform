let colors = {};

function color(name, value)
{
    if(value === undefined) {
        return colors[name];
    }
    return colors[name] = value;
};

function colorNames()
{
    var colorNames = [];
    for(var k in colors) {
        colorNames.push(k);
    }
    return colorNames;
}

function getColors()
{
    return colors;
}

function Colors()
{

}
Colors.set = function(name, value) {
    return colors[name] = value;
};
Colors.get = function(name) {
    return colors[name];
};
Colors.names = function()
{
    var colorNames = [];
    for(var k in colors) {
        colorNames.push(k);
    }
    return colorNames;
};
Colors.all = function()
{
    return colors;
}

export default Colors;