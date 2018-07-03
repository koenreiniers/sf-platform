import colors from './utils/color';

import applyWidgets from './utils/widget';
import router from './utils/router';
import http from './http';
import flash from './utils/flash';
import App from './classes/app';

function Raw()
{
}


applyWidgets(Raw);

Raw.colors = colors.all;
Raw.colorNames = colors.names;
Raw.color = function(name, value){
    if(value === undefined) {
        return colors.get(name);
    }
    colors.set(name, value);
    return colors.get(name);
};

export {
    App, colors, http, router, flash
};

export default Raw;

window.Raw = Raw;

