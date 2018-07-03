import Vue from 'vue';
import $ from 'jquery';
import app from 'app';
import http from './http';
import router from './utils/router';
import _ from 'lodash';

function Locator()
{

    function makeid(len)
    {
        len = len || 12;
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for( var i= 0; i < len; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    this.locate = function(name) {
        var $elm = $('[data-template-name="'+name+'"]');
        if(!$elm.attr('id')) {
            $elm.attr('id', makeid());
        }
        return '#'+$elm.attr('id');
    }

}

var locator = new Locator();

Vue.locate = locator.locate;

var templating = {
    locate: locator.locate,
};

export {
    locator as locator,
};

export default templating;