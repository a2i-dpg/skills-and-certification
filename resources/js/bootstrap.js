window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('select2');
    require('select2/dist/js/i18n/bn');
    window.toastr = require('toastr');

    require("flatpickr/dist/flatpickr.min");
    window.moment = require('moment-timezone');

    require('jquery-validation/dist/jquery.validate.js');
    require('jquery-validation/dist/additional-methods.js');
    window.helpers = require('./helpers.js');


} catch (e) {
}
