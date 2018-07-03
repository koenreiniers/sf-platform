var VueForm = function()
{

};
(function(VueForm){
    var types = {};

    function VueFormType(name)
    {
        this.name = name;

        this.extend = function(config)
        {
            for(var k in config) {
                this[k] = config[k];
            }
            return this;
        }
    }
    VueForm.type = function(name, config)
    {
        var type = types[name];
        if(type === undefined) {
            types[name] = type = new VueFormType(name);
        }
        if(config !== undefined) {
            type.extend(config);
        }
        return type;
    }
})(VueForm);

export default VueForm;