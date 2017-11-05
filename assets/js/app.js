var $ = require('jquery');
require('popper.js');
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});