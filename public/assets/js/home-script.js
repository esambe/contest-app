$(function(){
    'use strict'

    var dateFormat = 'mm/dd/yy',
        from = $('#dateFrom')
            .datepicker({
                defaultDate: '+1w',
                numberOfMonths: 1
            })
            .on('change', function () {
                to.datepicker('option', 'minDate', getDate(this));
            }),
        to = $('#dateTo').datepicker({
            defaultDate: '+1w',
            numberOfMonths: 1
        })
            .on('change', function () {
                from.datepicker('option', 'maxDate', getDate(this));
            });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }
        return date;
    }
});

$(document).ready(function () {

    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search options'
    });

    // $.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static';

    $('#mtn').show();
    $('#orange').hide();

    $('#payment-option').change(function () {
        if ($('#payment-option').val() == 'mtn') {
            $('#orange').hide();
            $('#mtn').show('slow');
        }
        if ($('#payment-option').val() == 'orange') {
            $('#mtn').hide();
            $('#orange').show('slow');
        }
    });

    // Datatable to display contests

    $('#contest').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

    $('#contest-user').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

});



