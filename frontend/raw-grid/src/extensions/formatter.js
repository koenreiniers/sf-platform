
function Formatter()
{
    this.format = function(value) {
        return value;
    };
    this.extend = function(config) {
        var self = this;
        var merged = new Formatter();
        merged.format = function(value) {
            value = self.format(value);
            value = config.format(value);
            return value;
        };
        return merged;
    }
}

function FormatterExtension()
{

}

let formatters = {};

FormatterExtension.get = function(name)
{
    if(!formatters[name]) {
        throw 'Formatter "' + name + '" does not exist';
    }
    return formatters[name];
}

FormatterExtension.set = function(name, config)
{
    let formatter = formatters[name];
    if(config === undefined) {
        if(formatter === undefined) {
            throw 'Formatter "' + name + '" does not exist';
        }
        return formatter;
    }
    formatter = new Formatter();
    formatter.name = name;
    return formatters[name] = formatter.extend(config);
}

export default FormatterExtension;