jQuery(document).ready(function($){
		var dateToday = new Date();
		var dates = $("#_ld_sell_from, #_ld_sell_to").datepicker({
	    defaultDate: "+1w",
	    hangeMonth: true,
	    dateFormat : 'yy-mm-dd',
	   	numberOfMonths: 3,
	    minDate: dateToday,
	    onSelect: function(selectedDate) {
        var option = this.id == "_ld_sell_from" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    	}
		}); 

		$('#_ld_pickup1').timepicker({ 'timeFormat': 'G:ia' });
		$('#_ld_pickup2').timepicker({ 'timeFormat': 'G:ia'});
	});