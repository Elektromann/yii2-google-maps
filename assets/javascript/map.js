function addMap(id, options)
{
    var geocoding = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById(id), options.mapOptions);
    
    if(options.geocodeOptions !== false) {
        geocoding.geocode(options.geocodeOptions, function(results, status)
        {
            if (status === google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
            }
        });
    }
    
    if(options.markers !== false) {
        var markers = options.markers;
        
        for(var i in markers) {
            
            marker = new google.maps.Marker({ map: map });
            
            if(markers[i].title !== null) {
                marker.setTitle(markers[i].title);
            }
            
            if(markers[i].description !== null) {
                markers[i].infoWindow = new google.maps.InfoWindow({ content: markers[i].description });
                (function(marker, i){
                marker.addListener('click', function() {
                    markers[i].infoWindow.open(map, marker);
                });
                })(marker, i);
            }
            
            if(typeof markers[i].location === 'string') {
                
                (function(marker, i){
                    geocoding.geocode({
                        "address": markers[i].location
                        }, function (results, status) {

                            if (status === google.maps.GeocoderStatus.OK) {
                                marker.setPosition(results[0].geometry.location);
                        }
                    });
                })(marker, i);
            } else {
                position = new google.maps.LatLng(markers[i].location[0], markers[i].location[1]);
                marker.setPosition(position);
            }
        }
    }
}