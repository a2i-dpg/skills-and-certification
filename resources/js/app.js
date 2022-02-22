require('./bootstrap');
const monthSelectPlugin = require("flatpickr/dist/plugins/monthSelect");

const getLocaleCurrency = function (userLocale, userCurrency, money) {
    return new Intl.NumberFormat(userLocale, { style: 'currency', currency: userCurrency }).formatToParts(money).map(val => val.value).join('');
}
window.getLocaleCurrency = getLocaleCurrency;

$(document).ready(function () {
    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
    });

    $(".flat-date").each(function () {
        let format = $(this).data('format');
        $(this).flatpickr({
            altInput: true,
            altFormat: "j F, Y",
            dateFormat: format ? format : "Y-m-d",
        });
    });

    $(".flat-time").each(function () {
        let format = $(this).data('format');
        $(this).flatpickr({
            enableTime: true,
            noCalendar: true,
            altInput: true,
            altFormat: "h:i K",
            dateFormat: format ? format : "H:i",
        });
    });

    $(".flat-month").each(function () {
        let format = $(this).data('format');
        $(this).flatpickr({
            altInput: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, //defaults to false
                    dateFormat: format ? format : "Y-m-d",
                    altFormat: "F Y", //defaults to "F Y"
                })
            ]
        });
    });

    $(".flat-datetime").each(function (key) {
        let format = $(this).data('format');
        $(this).flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: "j F, Y at h:i K",
            dateFormat: format ? format : "Y-m-d H:i",
        });
    });

    $('.select2-ajax-wizard').change(function () {
        if ($(this).parent().hasClass('has-error')) {
            $(this).valid();
        }
    });


    $('select.select2').each(function () {
        let placeholder = $(this).data('placeholder');
        $(this).select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: placeholder || 'select',
            allowClear: true,
            debug: true,
            language: 'en',
        });
    });
});

window.initializeSelect2 = function (selector) {
    $(document).find(selector).each(function () {
        const elm = $(this);
        const formElem = $(this).closest('form');
        const elmNameRefs = (elm.prop('id') || elm.prop('name'));
        const hasId = !!elm.prop('id');
        /**
         * base64_encode(\Softbd\Acl\Models\User::class)
         * @type {*|jQuery}
         */
        const model = elm.data('model');

        /**
         * {name_en} - {institute.title_en}
         * @type {*|jQuery|string}
         */
        const labelFields = elm.data('label-fields') || '';

        /**
         * user_type_id:#user_type_id|name_en
         * @type {*|jQuery}
         */
        const dependOn = elm.data('depend-on');

        /**
         * user_type_id:#user_type_id|name_en
         * @type {*|jQuery}
         */
        const dependOnOptional = elm.data('depend-on-optional');

        /**
         * acl|bcl
         * @type {*|jQuery}
         */
        const scopes = elm.data('scopes');

        /**
         * #name_en|#name_bn
         * @type {*|jQuery}
         */
        const dependentFields = elm.data('dependent-fields');

        /**
         * json_encode(['text' => 'Baker Hasan', 'id' => 1])
         * @type {*|jQuery}
         */
        const preselectedOption = elm.data('preselected-option');

        /**
         * @type {*|jQuery|string}
         */
        const placeholder = elm.data('placeholder') || window.select_option_placeholder;


        /**
         * name_en|institutes.title
         * @type {string}
         */
        let columns = (labelFields.match(/\{([^}]*)\}/g) || [])
            .join('|')
            .replaceAll('{', '')
            .replaceAll('}', '');


        if (typeof model === 'undefined' || !model.length) {
            console.error('Model is empty. NB: ' + elmNameRefs);
            return false;
        }
        if (!columns.length) {
            console.error('label field is undefined or invalid format. NB: ' + elmNameRefs);
        }

        elm.select2({
            placeholder,
            theme: 'bootstrap4',
            // delay: 250,
            width: '100%',
            allowClear: true,
            debug: true,
            language: 'en',
            ajax: {
                cache: false,
                method: 'post',
                url: '/web-api/model-resources',
                data: function (params) {
                    let query = {
                        search: params.term || '',
                        page: params.page || 1
                    };

                    /**
                     * json_encode(['name' => 'Baker Hasan'])
                     * @type {*|jQuery|{}}
                     */
                    const filters = elm.data('filters') || {};

                    if (typeof dependOn !== 'undefined' && dependOn.length) {
                        let parsedDependOn = dependOn.split('|');
                        parsedDependOn.forEach(function (item) {
                            let parsedItem = item.split(':');
                            let dependOnField, dependOnColumn;

                            if (parsedItem.length === 1) {
                                // if (/^[#.\[]/.test(parsedItem[0])) {
                                //
                                // }
                                dependOnColumn = parsedItem[0];
                                if (hasId) {
                                    dependOnField = "#" + parsedItem[0];
                                } else {
                                    dependOnField = "[name=" + parsedItem[0] + "]";
                                }
                            } else if (parsedItem.length === 2) {
                                dependOnColumn = parsedItem[0];
                                dependOnField = parsedItem[1];
                                if (!/^[#.\[]/.test(parsedItem[1])) {
                                    console.log("invalid selector. NB: " + elmNameRefs);
                                }
                            }
                            if (typeof dependOnField === 'undefined' || typeof dependOnColumn === 'undefined') {
                                console.log("Invalid selector. NB: " + elmNameRefs);
                            }

                            filters[dependOnColumn] = formElem.find(dependOnField).val() || '-------';
                        });
                    }

                    if (typeof dependOnOptional !== 'undefined' && dependOnOptional.length) {
                        let parsedDependOnOptional = dependOnOptional.split('|');
                        parsedDependOnOptional.forEach(function (item) {
                            let parsedItem = item.split(':');
                            let dependOnField, dependOnColumn;
                            if (parsedItem.length === 1) {
                                dependOnColumn = parsedItem[0];
                                if (hasId) {
                                    dependOnField = "#" + parsedItem[0];
                                } else {
                                    dependOnField = "[name=" + parsedItem[0] + "]";
                                }
                            } else if (parsedItem.length === 2) {
                                dependOnColumn = parsedItem[0];
                                dependOnField = parsedItem[1];
                                if (!/^[#.\[]/.test(parsedItem[1])) {
                                    console.log("invalid selector. NB: " + elmNameRefs);
                                }
                            }
                            if (typeof dependOnField === 'undefined' || typeof dependOnColumn === 'undefined') {
                                console.log("Invalid selector. NB: " + elmNameRefs);
                            }
                            let val = formElem.find(dependOnField).val();
                            if (typeof val !== 'undefined' && val?.length) {
                                filters[dependOnColumn] = val;
                            }
                        });
                    }

                    query['resource'] = {
                        type: 'select2',
                        model,
                        columns,
                        filters,
                        scopes,
                        select2: {
                            key_field: elm.data('key-field') || 'id',
                            text_field_format: labelFields
                        }
                    };
                    return query;
                }
            },
        });

        if (typeof preselectedOption !== 'undefined' && preselectedOption.hasOwnProperty('text') && preselectedOption.hasOwnProperty('id')) {
            elm.append(new Option(preselectedOption.text, preselectedOption.id, true, true)).trigger('change');
        }

        $(this).on('select2:select', function (e) {
            if (typeof dependentFields !== undefined && dependentFields?.length) {
                dependentFields.split('|').forEach(function (item) {
                    let elem = formElem.find(item);
                    if (elem && elem.length > 0) {
                        elem.val(null).trigger('change');
                    }
                });

                let data = e.params.data;
                let currTargetElem = $(e.currentTarget);
                currTargetElem.find("option[value='" + data.id + "']").attr('selected', 'selected');
            }
        });

        $(this).on('select2:unselect', function (e) {
            var data = e.params.data;
            let currTargetElem = $(e.currentTarget);
            currTargetElem.find("option[value='" + data.id + "']").attr('selected', false);
        });
    });
}

$(function () {
    initializeSelect2(".select2-ajax-wizard");
});

