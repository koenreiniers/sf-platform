'use strict';

function GridSources()
{

}
let sources = {};
GridSources.source = function(type, config)
{
    if(config === undefined) {
        return sources[type];
    }
    sources[type] = config;
};

export default GridSources;