console.log('hello');

// store my reactive data
let appData = {
    posts: [],
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
        'task_002': {
            label: 'Posts',
            value: 'task_002',
        },
        'user_key': {
            label: 'User Key',
            value: 'user_key',
        },
        'script': {
            label: 'Script',
            value: 'script',
        },
        'user_email': {
            label: 'Email',
            value: 'user_email',
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
        'task_002': {
            title: 'Posts Form',
            label_submit: 'LIST POSTS',
            post_processing: 'refresh_posts',
            inputs: [{
                name: 'c',
                value: 'admin',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'posts_read',
                type: 'hidden',
            }, {
                name: 'post_type',
                value: 'page',
                placeholder: 'Post Type',
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
                label: 'Pages (urls)',
                value: "home\nnews\nproducts\nservices\ncontact\ncredits",
                type: 'textarea',
                placeholder: 'enter your pages here',
            }, {
                name: 'option_page_on_front',
                label: 'Home Page',
                value: 'home',
                placeholder: 'HomePage',
            }, {
                name: 'option_page_for_posts',
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
                name: 'posts',
                label: 'Articles (urls)',
                value: "article-1\narticle-2\narticle-3\narticle-4\narticle-5",
                type: 'textarea',
                placeholder: 'enter your articles here',
            }, {
                name: 'option_blogname',
                label: 'Blog Name',
                value: '',
                placeholder: 'Blog Name',
            }, {
                name: 'option_blogdescription',
                label: 'Blog Description',
                value: '',
                placeholder: 'Blog Description',
            }, {
                name: 'option_blog_public',
                label: 'Open to search engines (SEO)',
                value: 'on',
                placeholder: 'Open to search engines (SEO)',
            }, {
                name: 'option_date_format',
                label: 'Date Format',
                value: 'd/m/Y',
                placeholder: 'Date Format',
            }, {
                name: 'option_time_format',
                label: 'Time Format',
                value: 'H:i',
                placeholder: 'Time Format',
            }, {
                name: 'option_comments',
                label: 'Comments (x3)',
                value: 'off',
                placeholder: 'Comments',
            }, {
                name: 'option_show_avatars',
                label: 'Show Avatars',
                value: 'off',
                placeholder: 'Show Avatars',
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
                label: 'Expiration Time (in Days)',
                value: '100', // days
                placeholder: 'Expiration Time (in Days)',
            },],
        },
        'user_email': {
            title: 'User Email',
            label_submit: 'SEND EMAIL',
            inputs: [{
                name: 'c',
                value: 'user',
                type: 'hidden',
            }, {
                name: 'm',
                value: 'mail',
                type: 'hidden',
            }, {
                name: 'from',
                label: 'From',
                value: '',
                placeholder: 'From',
                type: 'email',
            }, {
                name: 'to',
                label: 'To',
                value: '',
                placeholder: 'To',
                type: 'email',
            }, {
                name: 'subject',
                label: 'Subject',
                value: '',
                placeholder: 'Subject',
            }, {
                name: 'body',
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

    // if api_keu is empty then show api_config
    if (this.api_key == '') {
        this.options_ui.show_api_config = true;
    }
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
    api_after(r, post_processing) {
        console.log('api_after ' + post_processing);
        if (post_processing == 'refresh_posts') {
            console.log(r);
            if (r?.data?.posts) {
                this.posts = r.data.posts;
                console.log(this.posts);
            }
            else {
                // empty the posts to refresh the view
                this.posts = [];
            }
        }
    },
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