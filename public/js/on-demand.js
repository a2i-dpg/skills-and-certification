/**
 * Unbind default keyup event from datatable.js and add only press enter event.
 * @param datatable // instance of datatable.js
 */

const bindDatatableSearchOnPresEnterOnly = function (datatable) {
    let dataTableElm = $(datatable.table().node());
    $(document, dataTableElm).on('focus', '.dataTables_filter input', function () {
        $(this).unbind().bind('keyup', function (e) {
            if (e.keyCode === 13) {
                datatable.search(this.value).draw();
            }
        });
    });
}

/**
 * @param options
 * @returns {{serverSide: boolean, dom: string, stateSave: boolean, ordering: boolean, searching: boolean, responsive: boolean, processing: boolean, lengthChange: boolean, paging: boolean, lengthMenu, ajax: {method: string, url: string}, info: boolean} & Pick<*, number|symbol>}
 */
const serverSideDatatableFactory = (options) => {
    const {
        url = '',
        method = 'POST',
        data = [],
        columns = [],
        columnDefs = [],
        buttons = ['colvis'],
        generateSerialNumber = true,
        serialNumberColumn = '0',
        download_buttons = [],
        ...otherOptions
    } = options;

    if (!url.length) {
        throw 'Url must be declared.';
    }
    if (!columns.length) {
        throw 'Columns must be declared.';
    }

    let predefinedTableOptions = {
        scrollX   : true,
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        stateSave: false,
        lengthChange: true,
        responsive: false,
        /*responsive: {
            details: {
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                            '<td>' + col.title + ':' + '</td> ' +
                            '<td>' + col.data + '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ?
                        $('<table class="compact  "/>').append(data) :
                        false;
                }
            }
        },*/
        lengthMenu: [10, 20, 50, 100, 200, 500],
        paging: true,
        info: true,
        ajax: {
            method: method,
            url: url,
            data: data,
        },
        dom: "<'d-flex align-content-center'<'flex-grow-1'<'btn-group'B>><'mb-0'l><'mb-0'f>> <'row'<'col-sm-12'tr>> <'row '<'col-sm-5'i><'col-sm-7'p>>"
    };

    predefinedTableOptions.columns = columns;
    predefinedTableOptions.columnDefs = columnDefs;

    predefinedTableOptions.rowCallback = function (nRow, aData, iDisplayIndex) {
        const oSettings = this.fnSettings();
        if (generateSerialNumber) {
            $(("td:eq(" + parseInt(serialNumberColumn) + ")"), nRow).text((oSettings._iDisplayStart + iDisplayIndex + 1).toString());
        }
        return nRow;
    };

    let predefinedButtons = [
        {
            extend: 'colvis',
            text: '<i class="fa fa-eye"></i>',
            className: 'btn btn-sm btn-primary',
            titleAttr: 'Column visibility',
            /*attr: {
                id: 'datatable-column-visibility-btn'
            },*/
            init: function (dt, node, config) {
                $(node).removeClass('btn-secondary');
            }
        }
    ];

    let renderedButtons = [];
    let allButtons = [...buttons, ...download_buttons];

    allButtons.forEach((item) => {
        if (typeof item === 'string' && item) {
            let findButton = predefinedButtons.find((button) => button.extend === item);
            if (findButton) {
                renderedButtons.push(findButton);
            } else {
                renderedButtons.push(item);
            }
        } else if (typeof item === 'object') {
            renderedButtons.push(item);
        }
    });
    otherOptions.buttons = renderedButtons;

    return Object.assign({}, predefinedTableOptions, otherOptions);
}

$.validator.setDefaults({
    errorElement: "em",
    onkeyup: false,
    ignore: [],
    errorPlacement: function (error, element) {
        error.addClass("help-block");
        element.parents(".form-group").addClass("has-feedback");

        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else if (element.hasClass('select2-ajax') || element.hasClass('select2') || element.hasClass('select2-ajax-custom')) {
            error.insertAfter(element.parents(".form-group").find('.select2-container'));
        } else if (element.parents('.form-group').find('.input-group').length) {
            error.insertAfter(element.parents('.form-group').find('.input-group'));
        } else {
            error.insertAfter(element);
        }
    },
    success: function (label, element) {
    },
    highlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
    },
});

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

window.ajaxFailedResponseHandler = function (xhr) {
    if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON.errors !== 'undefined' && Array.isArray(xhr.responseJSON.errors) && xhr.responseJSON.errors.length) {
        xhr.responseJSON.errors.forEach(function (error) {
            toastr.error(error);
        })
    } else if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON.data !== 'undefined') {
        toastr.error(xhr.responseJSON.data);
    } else if (typeof xhr.responseJSON !== 'undefined' && Array.isArray(xhr.responseJSON) && xhr.responseJSON.length) {
        xhr.responseJSON.forEach(function (error) {
            toastr.error(error);
        })
    } else if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON && xhr.responseJSON.message !== 'undefined' && xhr.responseJSON.message) {
        toastr.error(xhr.responseJSON.message);
    } else if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON) {
        toastr.error(xhr.responseJSON);
    } else {
        toastr.error(xhr.responseText);
    }
}

$(document).ready(function () {
    /*--------Back to top js--------*/
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});
