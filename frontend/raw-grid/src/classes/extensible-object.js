function ExtensibleObject(name, config)
{
    this.name = name;
    for(var k in config) {
        this[k] = config[k];
    }
    this.extend = function(ext)
    {
        var newConfig = {};
        for(var k in config) {
            newConfig[k] = config[k];
        }
        for(var k in ext) {
            newConfig[k] = ext[k];
        }
        return newConfig;
    }
}

export default ExtensibleObject;