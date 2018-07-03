import config from './config';
import App from '../../raw/src/classes/app';
import './grid/formatters';

var app = new App(config);
export default app;
window.app = app;