jQuery(function($){
	fromdate = $('#from_date').val()
	todate = $('#max_date').val()
	var maxDate = new Date(todate);
	var dateToday = new Date();
	var fromDate;

	if(fromdate < dateToday){
		fromDate = new Date();
	}
	else{
		fromDate = new Date(fromdate);
	}

		var dates = $("#delivery_date").datepicker({
		defaultDate: "+1w",
			 	hangeMonth: true,
			 	dateFormat: 'yy-mm-dd',
			 	numberOfMonths: 3,
			 	minDate: fromDate,
			 	maxDate: maxDate,
			 	onSelect: function(selectedDate) {
			     	var option = this.id == "from_date" ? "minDate" : "maxDate",
			            instance = $(this).data("datepicker"),
			            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepic_defaults.dateFormat, selectedDate, instance.settings);
			     	dates.not(this).datepicker("option", option, date );
			    }
			});
	});
