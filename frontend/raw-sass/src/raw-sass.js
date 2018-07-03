import app from 'app';
import { http, router } from 'raw';

let appName = app.param('name');

var url = router.generate('raw_sass.app.view', {
    name: appName,
});


http.get(url).then(function(response){

    var app = response.data;
    var colors = app.variables.colors;
    for(var colorName in colors) {
        if(!colors.hasOwnProperty(colorName)) {
            continue;
        }
        var colorCode = colors[colorName];
        Raw.color(colorName, colorCode);
    }
});

export default {};