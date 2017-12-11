jQuery(function($){
	var loader = ajaxpagination.loader;
	$(document).on('click','.pagi-nav a',function(e){
		var ajaxUrl = ajaxpagination.ajaxurl;
		e.preventDefault();
		var page = $(this).data('load');
		var maxpage = $(this).data('max');

		$('.list-restaurant').append('<div class="loader col-md-12"><img class="m-auto" src="'+loader+'"></div>').fadeIn();

		$.ajax({
			url : ajaxUrl,
			type : 'post',
			data: {
				action : 'ajax_pagination',
				page : page,
				maxpage : maxpage
			},
			success:function(response){
				console.log(response);
				$('.loader').hide();
				$('.list-restaurant').append(response).hide().fadeIn();
				if(maxx.max == 2){
					$('.load-more').hide();
				}
			}

		});
	})
});