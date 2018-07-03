import $ from 'jquery';
window.jQuery = window.$ = $;

import moment from 'moment';
window.moment = moment;

import Vue from 'vue';
window.Vue = Vue;

import 'bootstrap';
require('bootstrap-daterangepicker');
require('jquery-serializejson');
require('jquery-ui');
require('gridstack');
require('gridstack.jquery-ui');

import './src/jquery/sidebar';