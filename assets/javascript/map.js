function addMap(id, options)
{
    var geocoding = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById(id), options.mapOptions);
    
    geocoding.geocode(options.geocodeOptions, function(results, status)
    {
        if (status === google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
        }
    });
}