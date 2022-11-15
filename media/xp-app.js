console.log('hello');

// store my reactive data
let appData = {
    app_json: {},
    active_menu: 'default',
    active_form: null,
    forms: {
        'default': {
            title: 'Default Form',
            label_submit: 'SEND API REQUEST',
            inputs: [{
                name: 'api_url',
                value: '/wp-admin/admin-ajax.php',
                placeholder: 'API URL',
            }, {
                name: 'api_key',
                value: '',
                type: 'password',
                placeholder: 'API Key',
            }, {
                name: 'action',
                value: 'xpress', // NEEDED BY WP admin-ajax.php
                placeholder: 'Action',
            }, {
                name: 'c',
                value: 'public',
                placeholder: 'class',
            }, {
                name: 'm',
                value: 'test',
                placeholder: 'Method',
            }, {
                name: 'code',
                value: '',
                type: 'textarea',
                placeholder: 'Code',
            }, ],
        },
        'contact': {
            title: 'Contact Form',
            label_submit: 'SEND CONTACT FORM',
            inputs: [{
                name: 'c',
                value: 'public',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'test',
                type: 'hidden',
            }, {
                name: 'name',
                value: '', 
                placeholder: 'Name',
            }, {
                name: 'email',
                value: '',
                placeholder: 'Email',
            }, {
                name: 'message',
                value: '',
                type: 'textarea',
                placeholder: 'Message',
            }, ],
        },
    },
    api_key: '',
    api_url: '/wp-admin/admin-ajax.php',
    window_w: window.innerWidth,
    window_h: window.innerHeight,
    message: 'Vue is everywhere!'
}

let created = function() {
    console.log('created');
    // load json data from #appJson
    let appJson = document.getElementById('appJson');
    if (appJson) {
        this.app_json = JSON.parse(appJson.innerHTML);
        console.log(this.app_json);
        this.api_key = this.app_json.api_key;
    }

    // WARNING: REGISTER COMPONENTS BEFORE MOUNTING
    let xp_url = this.app_json.xp_url;
    let compos = appTemplate?.getAttribute("data-compos");
    if (compos) {
        compos = compos.split(' ');
        compos.forEach(function(name) {
            app.component(
                'av-' + name,
                vue.defineAsyncComponent(() => import(`${xp_url}/vue-compos.php?name=av-${name}&ext=.js`))
            );
        });
    }

    // set active form
    this.active_form = this.forms['default'];
}

let mounted = function() {
    console.log('mounted');

    // add resize event listener
    window.addEventListener('resize', () => {
        this.window_w = window.innerWidth;
        this.window_h = window.innerHeight;
        this.message = '' + this.window_w + 'x' + this.window_h;
    });

    this.test('test1')
    this.message = '' + this.window_w + 'x' + this.window_h;

    // test api
    this.api({
        action: 'xpress',
        m: 'test',
        message: this.message,
    });
}

let methods = {
    async api(inputs) {
        if (!this.api_url) {
            return;
        }

        let formData = new FormData();
        // add inputs to FormData
        for (let key in inputs) {
            formData.append(key, inputs[key]);
        }
        // default api_url can be overriden by local inputs
        // add api_url if missing or empty in formData
        if (!formData.has('api_url') || !formData.get('api_url')) {
            formData.append('api_url', this.api_url);
        }
        // add api_key if missing or empty in formData
        if (!formData.has('api_key') || !formData.get('api_key')) {
            formData.append('api_key', this.api_key);
        }
        // add action = xpress if missing or empty in formData
        if (!formData.has('action') || !formData.get('action')) {
            formData.append('action', 'xpress');
        }

        let data = null;
        let request_url = formData.get('api_url');
        if (request_url) {
            try {
                // send request
                let response = await fetch(request_url, {
                    method: 'POST',
                    body: formData
                });
                data = await response.json();
                console.log(data);
            } catch (e) {
                console.log(e);
            }
        }

        return data;
    },
    test(msg = '') {
        console.log('HELLO FROM APP: ' + msg);
    }
}

// add vuejs app from CDN
import * as vue
from "./vue.esm-browser.prod.js";

const compoApp = vue.defineComponent({
    template: "#appTemplate",
    data: () => appData,
    provide() {
        return {
            // tricky way to pass 'this' to child components
            avroot: this,
        }
    },
    methods,
    created,
    mounted,
});

let app = vue.createApp(compoApp);
app.mount("#appContainer");