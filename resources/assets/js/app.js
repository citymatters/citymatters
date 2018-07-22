require('bootstrap');
require('jquery');
let ClipboardJS = require('clipboard');

jQuery(function () {
    jQuery('[data-toggle="tooltip"]').tooltip()
});

new ClipboardJS('.copytoclipboard');