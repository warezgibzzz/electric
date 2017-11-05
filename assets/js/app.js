import {Connection} from 'autobahn';

let $ = require('jquery');
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});

let connection = new Connection({
    type: 'websocket',
    url: 'ws://' + window.location.hostname + ':9089',
    realm: 'game'
});

let SESSION = {};
let connected = false;

connection.onopen = (session) => {
    console.log('Connected.', session);

    $(document).on('click', '.cell', (e) => {
        let cell = $(e.currentTarget);
        console.log(cell.data('pos'));
        try {
            session.call('ru.electric.click', cell.data('pos')).then(
                function (res) {
                    console.log("Result:", res);
                }
            );
        } catch (e) {
            console.log(e);
        }
    });

    connected = true;
};

connection.onclose = (reason, details) => {
    console.log('Connection closed', reason, details);
    connected = false;
};

connection.open();


