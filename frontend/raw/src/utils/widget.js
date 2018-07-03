import { deepCopy } from 'raw/std';

function apply(Raw){

    var widgets = {};

    function Widget(name)
    {
        this.icon = 'circle';
        this.description = '';
        this.settingsForm = {};
        this.component = null;
        this.defaultSettings = {};
        this.width = 6;
        this.height = 2;
        this.resizable = true;

        this.name = name;

        var widget = this;

        this.extend = function(config)
        {
            if(config === undefined) {
                return this;
            }
            for(var k in config) {
                this[k] = config[k];
            }
            return this;
        };

        this.newInstance = function(options) {
            options = options || {};

            var defaults = {
                type: this.name,
                title: '',
                settings: deepCopy(this.defaultSettings),
                dashboard: null,
            };
            for(var k in options) {
                defaults[k] = options[k];
            }

            return defaults;
        }
    };

    Raw.getWidgetTypes = function() {
        return widgets;
    }

    Raw.widget = function(name, config)
    {
        var widget = widgets[name];
        if(widget === undefined) {
            widget = new Widget(name);
        }
        if(config !== undefined) {
            widget.extend(config);
        }
        return widgets[name] = widget;
    }

};

export default apply;