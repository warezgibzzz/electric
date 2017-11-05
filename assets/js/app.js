import {Connection} from 'autobahn';
import {each} from "underscore";

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

    session.call('ru.electric.new').then(
        (res) => {
            console.log("Result:", res);
        },
        (error) => {
            console.log("Error:", error);
        }
    );

    $(document).on('click', '.cell', (e) => {
        let cell = $(e.currentTarget);
        console.log("Sent:", cell.data('pos'));

        session.call('ru.electric.click', [[cell.data('pos').toString()]]).then(
            (res) => {
                console.log("Result:", res);
                each(res.field, (data, key) => {
                    console.log(key, data);
                    if (data.is_on) {
                        $('.cell[data-pos="'+key+'"]').addClass('light-on');
                    } else {
                        $('.cell[data-pos="'+key+'"]').removeClass('light-on');
                    }
                });
                $(document).find('.scores-panel').text(res.counter);
            },
            (error) => {
                console.log("Error:", error);
            }
        );
    });

    connected = true;
};

connection.onclose = (reason, details) => {
    console.log('Connection closed', reason, details);
    connected = false;
};

connection.open();


