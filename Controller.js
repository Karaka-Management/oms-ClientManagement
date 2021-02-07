import { Autoloader } from '../../jsOMS/Autoloader.js';
import { NotificationMessage } from '../../jsOMS/Message/Notification/NotificationMessage.js';
import { NotificationType } from '../../jsOMS/Message/Notification/NotificationType.js';

Autoloader.defineNamespace('jsOMS.Modules');

jsOMS.Modules.ClientManager = class {
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
        const e    = typeof id === 'undefined' ? document.getElementsByTagName('canvas') : [document.getElementById(id)],
            length = e.length;

        for (let i = 0; i < length; ++i) {
            if (e[i].getAttribute('data-chart') === null
                && e[i].getAttribute('data-chart') !== 'undefined'
            ) {
                continue;
            }

            this.bindElement(e[i]);
        }
    };

    bindElement (chart)
    {
        if (typeof chart === 'undefined' || !chart) {
            jsOMS.Log.Logger.instance.error('Invalid chart: ' + chart, 'ClientManagementController');

            return;
        }

        const self = this;
        const data = JSON.parse(chart.getAttribute('data-chart'));

        const myChart = new Chart(chart.getContext('2d'), data);
    };
};

window.omsApp.moduleManager.get('ClientManager').bind();
