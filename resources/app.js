import Tabulator from 'tabulator-tables';
import dialogPolyfill from 'dialog-polyfill';
import Chart from 'chart.js/auto';
import 'tippy.js/dist/tippy.css';
import tippy from 'tippy.js';

require('leaflet.markercluster')

window.htmx = require('htmx.org');
htmx.config.historyCacheSize = 0;

window.VanillaTilt = require('vanilla-tilt')
window.luxon  = require('luxon')
window.Tabulator = Tabulator;
window.Dialog = dialogPolyfill;
window.Chart = Chart
window.tippy = tippy

window.dialog = function (url, title) {
    htmx.ajax('GET', url, '#pop')
        .then(_ => {
            document.getElementById('pop-title').innerText = title
            document.getElementById('general-dialog').showModal()
        });
}

htmx.on('htmx:afterRequest', function (event) {
    if (event.detail.xhr.status === 200 && event.detail.requestConfig.headers['dialog-close']) {
        document.getElementById('general-dialog').close()
    }
});

window.df = function (date, format) {
    return luxon.DateTime.fromISO(date).toFormat(format);
}


window.editFn = function(cell, url) {
    fetch(url + cell.getRow().getData()[cell.getTable().options.index], {
        method:'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({[cell.getColumn().getField()]: cell.getValue()}),
    }).then(response => response.status === 204 ? response.text() : response.json())
        .then(data => {
            if (data) {
                cell.getTable().replaceData(data).then(function () {
                    //TODO: report successful change
                })
            } else {
                cell.getTable().replaceData().then(function () {
                    //TODO: report successful change
                })
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            cell.getTable().replaceData().then(function () {
                //TODO: report error
            });
        });
}
