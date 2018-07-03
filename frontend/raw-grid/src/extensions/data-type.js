
function DataTypeExtension()
{
}
let dataTypes = {};
DataTypeExtension.dataType = function(name, config)
{
    if(config === undefined) {
        return dataTypes[name];
    }
    if(!dataTypes[name]) {
        dataTypes[name] = {
            inputType: 'text',
            format: function(data) {
                return data;
            },
            operators: [],
        };
    }
    for(var k in config) {
        dataTypes[name][k] = config[k];
    }
    return this;
};

export default DataTypeExtension;