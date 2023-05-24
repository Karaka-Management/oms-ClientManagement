import { Autoloader } from '../../jsOMS/Autoloader.js';

Autoloader.defineNamespace('jsOMS.Modules');

jsOMS.Modules.ClientManagement = class {
    /**
     * @constructor
     *
     * @since 1.0.0
     */
    constructor  (app)
    {
        this.app = app;
    };

    bind (id)
    {
        const charts = typeof id === 'undefined' ? document.getElementsByTagName('canvas') : [document.getElementById(id)];
        let length   = charts.length;

        for (let i = 0; i < length; ++i) {
            if (charts[i].getAttribute('data-chart') === null
                && charts[i].getAttribute('data-chart') !== 'undefined'
            ) {
                continue;
            }

            this.bindChart(charts[i]);
        }

        const maps = typeof id === 'undefined' ? document.getElementsByClassName('map') : [document.getElementById(id)];
        length     = maps.length;

        for (let i = 0; i < length; ++i) {
            this.bindMap(maps[i]);
        }
    };

    bindChart (chart)
    {
        if (typeof chart === 'undefined' || !chart) {
            jsOMS.Log.Logger.instance.error('Invalid chart: ' + chart, 'ClientManagement');

            return;
        }

        const self = this;
        const data = JSON.parse(chart.getAttribute('data-chart'));

        const myChart = new Chart(chart.getContext('2d'), data);
    };

    bindMap (map)
    {
        if (typeof map === 'undefined' || !map) {
            jsOMS.Log.Logger.instance.error('Invalid map: ' + map, 'ClientManager');

            return;
        }

        const mapObj = new OpenLayers.Map(map.getAttribute('id'), {
            controls: [
                new OpenLayers.Control.Navigation(
                    {
                        zoomBoxEnabled: true,
                        zoomWheelEnabled: false
                    }
                ),
                new OpenLayers.Control.Zoom(),
                new OpenLayers.Control.Attribution()
            ]
        });

        mapObj.addLayer(new OpenLayers.Layer.OSM());

        const fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        const toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
        const position       = new OpenLayers.LonLat(map.getAttribute('data-lon'), map.getAttribute('data-lat')).transform(fromProjection, toProjection);
        const zoom           = 12;

        const markers = new OpenLayers.Layer.Markers("Markers");
        mapObj.addLayer(markers);

        markers.addMarker(new OpenLayers.Marker(position));

        mapObj.setCenter(position, zoom);
    };
};

window.omsApp.moduleManager.get('ClientManagement').bind();
