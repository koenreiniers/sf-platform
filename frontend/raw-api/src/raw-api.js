import app from 'app';
import { http } from 'raw';

http.on('request', function(request){
    if(!app.hasParam('api_token')) {
        return;
    }
    var token = app.param('api_token');
    request.headers.set('Authorization', 'Bearer ' + token);
});