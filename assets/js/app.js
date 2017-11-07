import {Connection} from 'autobahn';
import {each} from "underscore";

require('jquery');
require('jquery.cookie');
require('bootstrap');

function newGame(res) {
    if (typeof $.cookie('uniqid') === 'undefined') {
        $.cookie('uniqid', res.uniqid, {expires: 365});
    }
    console.log("Result:", res);
    console.log(res.uniqid);
    each(res.field, (data, key) => {
        if (data.is_on) {
            $(document).find('.cell[data-pos="' + key + '"]').addClass('light-on');
        } else {
            $(document).find('.cell[data-pos="' + key + '"]').removeClass('light-on');
        }
    });
    $(document).find('.scores-panel').text(res.counter);
}

function clickCell(res) {
    console.log("Result:", res);
    each(res.field, (data, key) => {
        if (data.is_on) {
            $(document).find('.cell[data-pos="' + key + '"]').addClass('light-on');
        } else {
            $(document).find('.cell[data-pos="' + key + '"]').removeClass('light-on');
        }
    });
    $(document).find('.scores-panel').text(res.counter);
}

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    let connection = new Connection({
        type: 'websocket',
        url: 'ws://' + window.location.hostname + ':9089',
        realm: 'game'
    });

    let SESSION = {};
    let connected = false;

    connection.onopen = (session) => {
        console.log('Connected.', session, $.cookie);

        let sessionArgs = [];
        if (typeof $.cookie('uniqid') !== 'undefined') {
            sessionArgs.push([$.cookie('uniqid')])
        }
        session.call('ru.electric.new', sessionArgs).then(
            (res) => {
                newGame(res)
            },
            (error) => {
                console.log("Error:", error);
            }
        );

        $(document).on('click', '.cell', (e) => {
            let cell = $(e.currentTarget);
            console.log("Sent:", cell.data('pos'));

            session.call('ru.electric.click', [[cell.data('pos').toString(), $.cookie('uniqid')]]).then(
                (res) => {
                    clickCell(res)
                },
                (error) => {
                    console.log("Error:", error);
                }
            );
        });

        $(document).on('click', '.btn-new', (e) => {
            if ($.removeCookie('uniqid')) {
                console.log('Game uid destroyed');
            }
            session.call('ru.electric.new').then(
                (res) => {
                    newGame(res)
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
});


