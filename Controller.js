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
        /*
        const map = document.getElementById('iMap');
        fetch(map.src).then(res => res.text()).then(data => {
            const parser = new DOMParser();
            const svg = parser.parseFromString(data, 'image/svg+xml').querySelector('svg');

            if (map.id) svg.id = map.id;
            if (map.className) svg.classList = map.classList;

            map.parentNode.replaceChild(svg, map);

            return svg;
        })
        .then(svg => {
            //svg.setAttribute('width', 100);
            //svg.setAttribute('height', 136);
            //svg.setAttribute('viewbox', '0 0 1000 1360');
            //svg.style.width = '100%';
        });
        */
    };
};

window.omsApp.moduleManager.get('ClientManager').bind();
