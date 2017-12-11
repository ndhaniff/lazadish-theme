jQuery(function($){
	var geocoder = new google.maps.Geocoder();
	var post, address;
    var ajaxUrl = $('#geolocater,#geolocater2').data('url');

	navigator.geolocation.getCurrentPosition(function(position) {
                post = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                geocoder.geocode({
                	'location' : post
                },function(results, status){
                	if (status == google.maps.GeocoderStatus.OK) {
                        address = results[0].formatted_address;
                        $('#usergeolocate,#usergeo').html(address);
                        var loc = {
                        	lat:results[0].geometry.location.lat(),
                        	lng:results[0].geometry.location.lng()
                        }
                        ajaxSend(loc);
                        ajaxSendMore(loc);
                    };
                });
            });

    function ajaxSend(loc){

        $.post(ajaxUrl,{
                    lat: loc.lat,
                    lng: loc.lng,
                    action: 'ld_save_location_query',
                    cache: false
                },function(response){
                    if (response){
                        $('#scanlocation').html(response);
                    }

                })
     }

    function ajaxSendMore(loc){

        $.post(ajaxUrl,{
                    lat: loc.lat,
                    lng: loc.lng,
                    action: 'ld_save_morelocation_query',
                    cache: false
                },function(response){
                    if (response){
                        $('#morelocation').html(response);
                    }

                })
     }

});