jQuery(function($) {
            var marker, geocode, map, geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow({
                size: new google.maps.Size(150, 50)
            });


            //notgeolocate
            var currenLoc = {
                lat:parseFloat($("#_ld_map_droplat").val()),
                lng:parseFloat($("#_ld_map_droplng").val())
            }
            var myLatlng = new google.maps.LatLng(currenLoc.lat, currenLoc.lng);


                // Other options for the map, pretty much selfexplanatory
                var mapOptions = {
                    zoom: 14,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                // Attach a map to the DOM Element, with the defined settings
                var map = new google.maps.Map(document.getElementById("ldish-map"), mapOptions);

                //dragable position
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: currenLoc
                });
                geocoder.geocode({
                    'location': currenLoc
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        address = results[0].formatted_address;
                        $('#location').val(address);
                        $('#_ld_map_droplat').val(currenLoc.lat);
                        $('#_ld_map_droplng').val(currenLoc.lng);
                    };
                });
 

                google.maps.event.addListener(marker, 'dragend', function(){
                    var lat = this.getPosition().lat();
                    var lng = this.getPosition().lng();
                    document.getElementById("_ld_map_droplat").value = lat;
                    document.getElementById("_ld_map_droplng").value = lng;
                    var dragpos = {
                        lat: lat,
                        lng: lng
                    };

                    geocoder.geocode({
                        'location': dragpos
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            address = results[0].formatted_address;
                            $('#_ld_map_address').val(address);
                        };
                    });
                });


            $('#_ld_map_address').on('focus',autoCompleteLocation);

            
            function autoCompleteLocation(){
                var input = document.getElementById('_ld_map_address');
                var options = {
                    types: ['(regions)'],
                    componentRestrictions: {
                        country: 'my'
                    }
                };

                autocomplete = new google.maps.places.Autocomplete(input, options);

                google.maps.event.addListener(autocomplete, 'place_changed',function(){
                    var addressmanual = $('#_ld_map_address').val();
                    geocoder.geocode({
                        'address': addressmanual
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            address = results[0].formatted_address;
                            marker = new google.maps.Marker({
                                map: map,
                                draggable: true,
                                animation: google.maps.Animation.DROP,
                                position: results[0].geometry.location
                            });

                            google.maps.event.addListener(marker, 'dragend', centerMap );
                            marker.setMap(map);
                            $('#_ld_map_address').val(address);
                            $('#_ld_map_droplat').val(results[0].geometry.location.lat);
                            $('#_ld_map_droplng').val(results[0].geometry.location.lng);
                        };
                    });
                });
            }
            //notgelocate


            $('#geolocateme').on('click',function(e){
                e.preventDefault();
                navigator.geolocation.getCurrentPosition(function(position) {
                var posit = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                initialize(posit);

                });
            });

            //search autocomplete


            function initialize(posit) {
                // Coordinates to center the map
                
                var myLatlng = new google.maps.LatLng(posit.lat, posit.lng);


                // Other options for the map, pretty much selfexplanatory
                var mapOptions = {
                    zoom: 14,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                // Attach a map to the DOM Element, with the defined settings
                var map = new google.maps.Map(document.getElementById("ldish-map"), mapOptions);

                //dragable position
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: posit
                });
                geocoder.geocode({
                    'location': posit
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        address = results[0].formatted_address;
                        $('#_ld_map_address').val(address);
                        $('#_ld_map_droplat').val(posit.lat);
                        $('#_ld_map_droplng').val(posit.lng);
                    };
                });

                function centerMap(){
                    var lat = this.getPosition().lat();
                    var lng = this.getPosition().lng();
                    document.getElementById("_ld_map_droplat").value = lat;
                    document.getElementById("_ld_map_droplng").value = lng;
                    var dragpos = {
                        lat: lat,
                        lng: lng
                    };

                    geocoder.geocode({
                        'location': dragpos
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            address = results[0].formatted_address;
                            $('#_ld_map_address').val(address);
                        };
                    });

                }

                google.maps.event.addListener(marker, 'dragend', centerMap );


            $('#_ld_map_address').on('focus',autoCompleteLocation);

            
            function autoCompleteLocation(){
                var input = document.getElementById('_ld_map_address');
                var options = {
                    types: ['(regions)'],
                    componentRestrictions: {
                        country: 'my'
                    }
                };

                autocomplete = new google.maps.places.Autocomplete(input, options);

                google.maps.event.addListener(autocomplete, 'place_changed',function(){
                    var addressmanual = $('#_ld_map_address').val();
                    geocoder.geocode({
                        'address': addressmanual
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            address = results[0].formatted_address;
                            marker = new google.maps.Marker({
                                map: map,
                                draggable: true,
                                animation: google.maps.Animation.DROP,
                                position: results[0].geometry.location
                            });

                            google.maps.event.addListener(marker, 'dragend', centerMap );
                            marker.setMap(map);
                            $('#_ld_map_address').val(address);
                            $('#_ld_map_droplat').val(results[0].geometry.location.lat);
                            $('#_ld_map_droplng').val(results[0].geometry.location.lng);
                        };
                    });
                });
            }
                
            }


        });
