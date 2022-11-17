console.log('hello');

// store my reactive data
let appData = {
    active_menu: null,
    menus: {
        'home': {
            label: 'Home',
            value: 'home',
        },
        'task_001': {
            label: 'Project Starter',
            value: 'task_001',
        },
        'user_key': {
            label: 'User Key',
            value: 'user_key',
        },
        'script': {
            label: 'Script',
            value: 'script',
        },
        'contact': {
            label: 'Contact',
            value: 'contact',
        },
    },
    options_ui: {},
    app_json: {},
    active_form: null,
    forms: {
        'home': {
            title: 'Home Form',
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
            },],
        },
        'script': {
            title: 'Script Form',
            label_submit: 'SEND SCRIPT',
            inputs: [{
                name: 'c',
                value: 'admin',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'script',
                type: 'hidden',
            }, {
                name: 'filename',
                value: '',
                placeholder: 'Filename',
            }, {
                name: 'extension',
                value: '',
                placeholder: 'Extension',
            }, {
                name: 'code',
                value: '',
                type: 'textarea',
                placeholder: 'enter your script here',
            }, {
                name: 'upload',
                placeholder: 'Upload',
                type: 'file',
            },],
        },
        'task_001': {
            title: 'Project Starter',
            label_submit: 'SAVE OPTIONS',
            inputs: [{
                name: 'c',
                value: 'admin',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'task_001',
                type: 'hidden',
            }, {
                name: 'pages',
                label: 'Pages',
                value: "home\nnews\nproducts\nservices\ncontact\ncredits",
                type: 'textarea',
                placeholder: 'enter your pages here',
            }, {
                label: 'Home Page',
                value: 'home',
                placeholder: 'HomePage',
            }, {
                name: 'blog_page',
                label: 'Blog Page',
                value: 'news',
                placeholder: 'Blog Page',
            }, {
                name: 'menu_primary',
                name: 'menu_primary',
                label: 'Main Menu',
                value: "home\nnews\nproducts\nservices\ncontact",
                type: 'textarea',
                placeholder: 'enter your main menu items here',
            }, {
                name: 'menu_secondary',
                name: 'menu_secondary',
                label: 'Footer Menu',
                value: "home\nnews\nproducts\nservices\ncontact\ncredits",
                type: 'textarea',
                placeholder: 'enter your footer menu items here',
            }, {
                name: 'option_date_format',
                label: 'Date Format',
                value: 'd/m/Y H:i:s',
                placeholder: 'Date Format',
            }, {
                name: 'comments',
                label: 'Comments',
                value: 'off',
                placeholder: 'Comments',
            }, {
                name: 'avatars',
                label: 'Avatars',
                value: 'off',
                placeholder: 'Avatars',
            },],
        },
        'user_key': {
            title: 'Create User Key',
            label_submit: 'CREATE USER KEY',
            inputs: [{
                name: 'c',
                value: 'admin',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'key_user_create',
                type: 'hidden',
            }, {
                name: 'user_c',
                label: 'Class',
                value: 'user',
                placeholder: 'Class',
            }, {
                name: 'user_tmax',
                label: 'Expiration Time',
                value: '3600000', // 1000 hours = 41 days
                placeholder: 'Expiration Time',
            },],
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
                label: 'Name',
                value: '',
                placeholder: 'Name',
            }, {
                name: 'email',
                label: 'Email',
                value: '',
                placeholder: 'Email',
            }, {
                name: 'message',
                label: 'Message',
                value: '',
                type: 'textarea',
                placeholder: 'Message',
            },],
        },
    },
    api_key: '',
    api_url: '/wp-admin/admin-ajax.php',
    window_w: window.innerWidth,
    window_h: window.innerHeight,
    message: 'Vue is everywhere!'
}

let created = function () {
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
        compos.forEach(function (name) {
            app.component(
                'av-' + name,
                vue.defineAsyncComponent(() => import(`${xp_url}/vue-compos.php?name=av-${name}&ext=.js`))
            );
        });
    }

    // set active form
    this.active_form = this.forms['home'];
    // set active menu
    this.active_menu = this.menus['home'];
}

let mounted = function () {
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
    async api(inputs, fd = null) {
        if (!this.api_url) {
            return;
        }

        let formData = fd;
        formData ??= new FormData();

        // Display the key/value pairs
        for (const pair of formData.entries()) {
            console.log(`${pair[0]}, ${pair[1]}`);
        }

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