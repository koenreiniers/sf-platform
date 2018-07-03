import app from 'app';

let Routing = window.Routing;

var router = {
    generate: function(route, params, absolute) {
        return Routing.generate(route, params, absolute);
    },
    asset: function(path) {
        return app.param('base_path') + '/' + path;
    },
};

export default router;