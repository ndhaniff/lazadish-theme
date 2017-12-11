jQuery(function($) {
    var dateToday = new Date();
    var dates = $("#ld_front_from, #ld_front_to, #ld_sell_from, #ld_sell_to").datepicker({
        defaultDate: "+1w",
        hangeMonth: true,
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 3,
        minDate: dateToday,
        onSelect: function(selectedDate) {
            var option = this.id == "ld_sell_from" ? "minDate" : "maxDate",
                instance = $(this).data("datepicker"),
                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });

    $('#pickup1,#_pickup1,#_last_time').timepicker({
        'timeFormat': 'G:ia'
    });
    $('#pickup2,#_pickup2').timepicker({
        'timeFormat': 'G:ia'
    });
});