import $ from 'jquery';
import app from 'app';
import templating from 'raw/templating';
import components from 'raw/components';
import http from '../http';
import Dispatcher from '../classes/dispatcher';






let dispatcher = new Dispatcher();



function Flash()
{
}

Flash.on = function(eventName, callback){
    return dispatcher.on(eventName, callback);
};
Flash.trigger = function(eventName, event){
    return dispatcher.trigger(eventName, event);
};

Flash.flash = function(level, message)
{
    Flash.trigger('flash', {
        level: level,
        message: message,
    });
    return;
    templating.load('flash/message').then(function(template){
        var html = template({
            level: level,
            message: message,
        });
        var $elm = $(html);



        $elm.find('[data-dismiss]').on('click', function(e){
            e.preventDefault();
            $elm.remove();
            if($container.children().length === 0) {
                $container.hide();
            }
        });

        $container.append($elm);
        $container.show();
    });
}

//http.on('response', function(response){
//    var flashMessages = response.headers.get('X-Flash-Messages');
//    if(flashMessages !== undefined) {
//        flashMessages = JSON.parse(flashMessages);
//        flashMessages.forEach(function(flashMessage){
//            Flash.flash(flashMessage.type, flashMessage.message);
//        });
//    }
//});



export default Flash;