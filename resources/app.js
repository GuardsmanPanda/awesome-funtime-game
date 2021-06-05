import { TabulatorFull } from "tabulator-tables";
import dialogPolyfill from 'dialog-polyfill';
import 'tippy.js/dist/tippy.css';
import tippy from 'tippy.js';


window.htmx = require('htmx.org');
htmx.config.historyCacheSize = 0;

window.VanillaTilt = require('vanilla-tilt')
window.Tabulator = TabulatorFull;
window.luxon  = require('luxon')
window.Dialog = dialogPolyfill;
window.tippy = tippy


window.df = function (date, format) {
    return luxon.DateTime.fromISO(date).toFormat(format);
}
