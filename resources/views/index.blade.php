@extends('layout.app')

@push('additionalHeadScripts')
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />
@endpush

@section('content')
    <div style="margin-top: -25px;">
        <h1>PM2.5 Belastung</h1>
        <table style="width: 100%;" cellpadding="5px">
            <tr>
                <td class="marker-value-very-low">0-15 μg/m3</td>
                <td class="marker-value-low">15-30 μg/m3</td>
                <td class="marker-value-medium">30-55 μg/m3</td>
                <td class="marker-value-high">55-110 μg/m3</td>
                <td class="marker-value-very-high">>110 μg/m3</td>
            </tr>
        </table>
        <div id="map" style="width: 100%; height: 70vh;">

        </div>
    </div>
@endsection

@push('additionalBodyScripts')
    <script>
        var marker = [];
        var popups = [];
        function updateMapMarkers() {
            var xhr = new XMLHttpRequest();
            var bounds = map.getBounds();
            xhr.open('GET', '/api/measpoints/byArea/' + bounds.getNorthWest().lat + '/' + bounds.getNorthWest().lng + '/' + bounds.getSouthEast().lat + '/' + bounds.getSouthEast().lng);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Updating measpoint markers");
                    var data = JSON.parse(this.responseText);
                    for(var i = 0; i < marker.length; i++)
                    {
                        marker[i].remove();
                    }
                    marker = [];
                    console.log(data.length + " measpoints fetched");
                    for(var i = 0; i < data.length; i++)
                    {
                        var markerHeight = 25, markerRadius = 25, linearOffset = 25;

                        var markerValue = 'very-low';

                        if(data[i].pm2 < 15)
                        {
                            markerValue = 'very-low';
                        }
                        else if(data[i].pm2 >= 15 && data[i].pm2 < 30)
                        {
                            markerValue = 'low';
                        }
                        else if(data[i].pm2 >= 30 && data[i].pm2 < 55)
                        {
                            markerValue = 'medium';
                        }
                        else if(data[i].pm2 >= 55 && data[i].pm2 < 110)
                        {
                            markerValue = 'high';
                        }
                        else if(data[i].pm2 >= 110)
                        {
                            markerValue = 'very-high';
                        }

                        marker[i] = new mapboxgl.Marker({
                            anchor: 'center',
                            element: document.createElement("span")
                        })
                            .setLngLat([data[i].lon, data[i].lat])
                            .setPopup(new mapboxgl.Popup()
                                .setLngLat([data[i].lon, data[i].lat])
                                .setHTML('<table cellpadding="4">' +
                                    '<tr>' +
                                    '<td>PM2.5:</td>' +
                                    '<td>' + data[i].pm2 + ' μg/m3</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>PM10:</td>' +
                                    '<td>' + data[i].pm10 + ' μg/m3</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Temperature:</td>' +
                                    '<td>' + data[i].temperature + '°C</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Humidity</td>' +
                                    '<td>' + data[i].humidity + '%rH</td>' +
                                    '</tr>' +
                                    '<tr><td colspan="2"><i>updated at '+ data[i].updated_at + '</i></td></tr>' +
                                    '</table>'))
                                .addTo(map);

                        marker[i].getElement().setAttribute('class', 'mapboxgl-marker mapboxgl-marker-anchor-center marker marker-value-' + markerValue)
                    }
                }
                setTimeout(updateMapMarkers, 1000);
            };
            xhr.send();
        }
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ3dhbGR2b2dlbCIsImEiOiJjamh1bXFkaGcwcDV4M2tuNThlZ3hiNG9kIn0.SkHO8yJb0C7-hF8L26hWIw';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: [11.753524, 48.3798401], //11.7530256, 48.3782372
            zoom: 16
        });
        map.addControl(new mapboxgl.NavigationControl(), 'top-left');
        updateMapMarkers();
    </script>
@endpush