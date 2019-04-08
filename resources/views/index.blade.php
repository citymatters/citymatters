@extends('layout.app')

@push('additionalHeadScripts')
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
@endpush

@push('additionalHeadScripts')
    <style type="text/css">
        #menu {
            background: #fff;
            border-radius: 3px;
            border: 1px solid rgba(0,0,0,0.4);
            font-family: 'Open Sans', sans-serif;
        }

        #menu a {
            font-size: 13px;
            color: #404040;
            display: block;
            margin: 0;
            padding: 0;
            padding: 10px;
            text-decoration: none;
            border-bottom: 1px solid rgba(0,0,0,0.25);
            text-align: center;
        }

        #menu a:last-child {
            border: none;
        }

        #menu a:hover {
            background-color: #f8f8f8;
            color: #404040;
        }

        #menu a.active {
            background-color: #3887be;
            color: #ffffff;
        }

        #menu a.active:hover {
            background: #3074a4;
        }
    </style>
@endpush

@section('title')
    city_matters
@endsection

@section('content')
    <div style="margin-top: -25px;">
        <div class="row">
            <div class="col-12">
                <!--<div class="row">
                    <div class="col-sd-12 col-md-10">
                        Zeitlicher Verlauf (<label id='day'></label>):
                        <input id='slider' type='range' min="0" max="31" value='{{ now()->day }}'>
                    </div>
                    <div class="col-sd-12 col-md-2" id="flycontainer">
                        Jump to:
                        <button id='fly'>Munich</button>
                        <button id='fly2'>Berlin</button>
                    </div>
                </div>-->
            </div>
            <div class="col-md-10 col-sm-12">
                <div id="map" style="width: 100%; height: 60vh;"></div>
            </div>
            <div class="col-md-2 col-sm-12">
                <nav id="menu"></nav>
            </div>
        </div>
        <div id="chart"></div>
        </div>
    </div>
@endsection

@push('additionalBodyScripts')
    <script>
        function getColorScaling(layer) {
            switch(layer) {
                case 'pm2':
                case 'pm10':
                    return [
                        'interpolate',
                        ['linear'],
                        ['get', layer],
                        0, '#79bc6a',
                        15, '#bbcf4c',
                        30, '#eec20b',
                        55, '#f29305',
                        110, '#e8416f',
                        10000, '#e8416f'
                    ];
                case 'ozone':
                case 'sulfurDioxide':
                case 'carbonMonoxide':
                case 'nitrogenDioxide':
                    return [
                        'interpolate',
                        ['linear'],
                        ['get', layer],
                        0, '#79bc6a',
                        100, '#e8416f',
                        100000, '#e8416f'
                    ];
                case 'humidity':
                    return [
                        'interpolate',
                        ['linear'],
                        ['get', layer],
                        0, '#79bc6a',
                        100, '#e8416f',
                        100000, '#e8416f'
                    ];
                case 'temperature':
                    return [
                        'interpolate',
                        ['linear'],
                        ['get', layer],
                        -30, '#0003bc',
                        0, '#63cdff',
                        15, '#79bc6a',
                        30, '#f29305',
                        60, '#e80944'
                    ];
            }
        }

        var toggleableLayerIds = [
            'pm2',
            'pm10',
            'humidity',
            'temperature',
        ];
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ3dhbGR2b2dlbCIsImEiOiJjamh1bXFkaGcwcDV4M2tuNThlZ3hiNG9kIn0.SkHO8yJb0C7-hF8L26hWIw';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: [7.839586, 47.996945],
            zoom: 12
        });

        function updateChart()
        {
            var bounds = map.getBounds();
            Plotly.d3.json('{{ url('/api/average') }}?days=30&latStart='
                + bounds.getNorthWest().lat
                + '&lonStart='
                + bounds.getNorthWest().lng
                + '&latEnd='
                + bounds.getSouthEast().lat
                + '&lonEnd='
                + bounds.getSouthEast().lng,
                function(err, data) {
                    var chartData = convertChartData(data);
                    Plotly.newPlot('chart', chartData, {});
                });
        }

        function updateLayers()
        {
            var bounds = map.getBounds();
            map.getSource('measpoints').setData('{{ url('/api/measpoints') }}?startTime='
                + (Math.round((new Date()).getTime() / 1000) - (86400 * 30))
                + '&latStart='
                + bounds.getNorthWest().lat
                + '&lonStart='
                + bounds.getNorthWest().lng
                + '&latEnd='
                + bounds.getSouthEast().lat
                + '&lonEnd='
                + bounds.getSouthEast().lng);
        }

        map.on('boxzoomend', function(e, b) {
            updateChart();
            updateLayers();
        });
        map.on('moveend', function(e, b) {
            updateChart();
            updateLayers();
        });

        function convertChartData(data) {
            var d = {
            };
            var chartData = [];
            for(var i = 0; i < 30; i++)
            {
                for (var a = 0; a < toggleableLayerIds.length; a++) {
                    if(!d[toggleableLayerIds[a]])
                    {
                        d[toggleableLayerIds[a]] = {
                            x: [],
                            y: [],
                            mode: 'lines+markers',
                            name: toggleableLayerIds[a]
                        };
                    }
                    d[toggleableLayerIds[a]].x.push(i);
                    if(data[i]) {
                        d[toggleableLayerIds[a]].y.push(data[i][toggleableLayerIds[a]]);
                    }
                    else
                    {
                        d[toggleableLayerIds[a]].y.push(null);
                    }

                }
            }

            for (var a = 0; a < toggleableLayerIds.length; a++) {
                chartData.push(d[toggleableLayerIds[a]]);
            }
            return chartData;
        }

        function filterBy(day) {

            var filters = ['==', 'day', day];
            for (var i = 0; i < toggleableLayerIds.length; i++) {
                var id = toggleableLayerIds[i];
                map.setFilter(id, filters);
            }

            document.getElementById('day').textContent = day;
        }

        map.on('load', function() {
            var bounds = map.getBounds();
            map.addSource('measpoints', {
                type: 'geojson',
                data: '{{ url('/api/measpoints') }}?startTime=' + (Math.round((new Date()).getTime() / 1000) - (86400 * 30))
                    + '&latStart='
                    + bounds.getNorthWest().lat
                    + '&lonStart='
                    + bounds.getNorthWest().lng
                    + '&latEnd='
                    + bounds.getSouthEast().lat
                    + '&lonEnd='
                    + bounds.getSouthEast().lng
            });
            for (var i = 0; i < toggleableLayerIds.length; i++) {
                var id = toggleableLayerIds[i];
                map.addLayer({
                    'id': id,
                    'type': 'circle',
                    'source': 'measpoints',
                    'layout': {
                        'visibility': (id == 'pm2' ? 'visible' : 'none')
                    },
                    'paint': {
                        'circle-color': getColorScaling(id),
                        'circle-opacity': .6,
                        'circle-radius': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            1, 1,
                            16, 20
                        ]
                    }
                });
            }
            map.addControl(new mapboxgl.NavigationControl(), 'top-left');
            filterBy({{ now()->day }});
            document.getElementById('slider').addEventListener('input', function(e) {
                var day = parseInt(e.target.value, 10);
                filterBy(day);
            });

            var bounds = map.getBounds();

            Plotly.d3.json('{{ url('/api/average') }}?days=30&latStart='
                + bounds.getNorthWest().lat
                + '&lonStart='
                + bounds.getNorthWest().lng
                + '&latEnd='
                + bounds.getSouthEast().lat
                + '&lonEnd='
                + bounds.getSouthEast().lng,
            function(err, data) {
                var chartData = convertChartData(data);
                Plotly.newPlot('chart', chartData, {});
            });
            document.getElementById('fly').addEventListener('click', function () {
                map.flyTo({
                    center: [11.419765, 48.147247]
                });
            });
            document.getElementById('fly2').addEventListener('click', function () {
                map.flyTo({
                    center: [13.272448, 52.500231]
                });
            });
        });

        for (var i = 0; i < toggleableLayerIds.length; i++) {
            var id = toggleableLayerIds[i];

            var link = document.createElement('a');
            link.href = '#';
            link.className = id == 'pm2' ? 'active' : '';
            link.textContent = id;

            link.onclick = function (e) {
                var clickedLayer = this.textContent;
                e.preventDefault();
                e.stopPropagation();

                var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

                if (visibility === 'visible') {
                    map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                    this.className = '';
                } else {
                    this.className = 'active';
                    map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                }
            };

            var layers = document.getElementById('menu');
            layers.appendChild(link);
        }

        map.on('click', 'pm2', function (e) {
            var coordinates = e.features[0].geometry.coordinates.slice();
            var description = '<table cellpadding="4">' +
                '<tr>' +
                    '<td>PM2.5:</td>' +
                    '<td>' + e.features[0].properties.pm2 + ' μg/m3</td>' +
                    '</tr>' +
                '<tr>' +
                    '<td>PM10:</td>' +
                    '<td>' + e.features[0].properties.pm10 + ' μg/m3</td>' +
                    '</tr>' +
                '<tr>' +
                    '<td>Temperature:</td>' +
                    '<td>' + e.features[0].properties.temperature + '°C</td>' +
                    '</tr>' +
                '<tr>' +
                    '<td>Humidity</td>' +
                    '<td>' + e.features[0].properties.humidity + '%rH</td>' +
                    '</tr>' +
                '</table>';

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }

            new mapboxgl.Popup()
                .setLngLat(coordinates)
                .setHTML(description)
                .addTo(map);
        });

        // Change the cursor to a pointer when the mouse is over the places layer.
        map.on('mouseenter', 'pm2', function () {
            map.getCanvas().style.cursor = 'pointer';
        });

        // Change it back to a pointer when it leaves.
        map.on('mouseleave', 'pm2', function () {
            map.getCanvas().style.cursor = '';
        });
    </script>
@endpush