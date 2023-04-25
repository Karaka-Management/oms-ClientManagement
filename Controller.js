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

        const self = this;

        map = new OpenLayers.Map(map.getAttribute('id'), {
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

        var mapnik         = new OpenLayers.Layer.OSM();
        var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
        var position       = new OpenLayers.LonLat(13.41,52.52).transform( fromProjection, toProjection);
        var zoom           = 15;

        map.addLayer(mapnik);
        map.setCenter(position, zoom );
    };
};

window.omsApp.moduleManager.get('ClientManagement').bind();
