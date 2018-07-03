import { Dispatcher } from 'raw/misc';
import Request from './request';
import Response from './response';
import $ from 'jquery';

function HttpClient() {

    var dispatcher = new Dispatcher();

    this.on = function(eventName, callback)
    {
        dispatcher.on(eventName, callback);
    };

    this.trigger = function(eventName, event)
    {
        dispatcher.trigger(eventName, event);
    };

    this.on('request', function(request){
        request.headers.set('Accept', 'application/json');
        request.headers.set('Content-Type', 'application/json; charset=utf-8');
    });

    this.send = function(request)
    {
        return new Promise((resolve, reject) => {
            dispatcher.trigger('request', request);

            var options = {
                type: request.method,
                url: request.url,
                data: request.body,
                headers: request.headers.all(),
            };
            $.ajax(options).done(function(response, status, xmlHttpRequest){
                var httpResponse = new Response(xmlHttpRequest);
                dispatcher.trigger('response', httpResponse);
                resolve(httpResponse);
            });
        });
    };

    this.request = function(method, url, body, headers) {
        if(body !== undefined) {
            body = JSON.stringify(body);
        }
        var request = new Request(method, url, body, headers);
        return this.send(request);
    };

    this.head = function(url)
    {
        return this.request('HEAD', url);
    };

    this.get = function(url) {
        return this.request('GET', url);
    };
    this.delete = function(url) {
        return this.request('DELETE', url);
    };
    this.post = function(url, data) {
        return this.request('POST', url, data);
    };
    this.patch = function(url, data) {
        return this.request('PATCH', url, data);
    };
    this.put = function(url, data) {
        return this.request('PUT', url, data);
    };

}

export default HttpClient;