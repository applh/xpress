<?php



?>
<!-- VUEJS CONTAINER -->
<div id="appContainer" class="appBox"></div>

<!-- VUEJS TEMPLATE CSS-->
<style type="text/css">
    .appBox {
        background-color: #eee;
        padding: 20px;
    }

    .appBox form {
        display: flex;
        flex-direction: column;
        max-width: 800px;
        flex-wrap: wrap;
    }

    .appBox form>* {
        margin: 0.5rem 0;
        padding: 0.5rem;
        width: 100%;
        display: block;
    }
</style>

<!-- VUEJS TEMPLATE -->
<template id="appTemplate" data-compos="box-sm box-md box-lg box-xl form-builder">
    <section>
        <h1>XPress</h1>
        <p><?php echo "($xp_url)" ?></p>
        <p class="pad4">{{ message }}</p>
        <form>
            <h3>api url</h3>
            <input type="text" v-model="api_url">
            <h3>api key</h3>
            <input type="password" v-model="api_key">
        </form>
        <av-box-sm v-if="window_w < 800"></av-box-sm>
        <av-box-md v-else-if="window_w < 1200"></av-box-md>
        <av-box-lg v-else-if="window_w < 1600"></av-box-lg>
        <av-box-xl v-else></av-box-xl>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module">
    console.log('hello');

    // store my reactive data
    let appData = {
        api_key: '<?php echo $xpress_api_key ?>',
        api_url: '/wp-admin/admin-ajax.php',
        window_w: window.innerWidth,
        window_h: window.innerHeight,
        message: 'Vue is everywhere!'
    }

    let created = function() {
        console.log('created');

        // WARNING: REGISTER COMPONENTS BEFORE MOUNTING
        let compos = appTemplate?.getAttribute("data-compos");
        if (compos) {
            compos = compos.split(' ');
            compos.forEach(function(name) {
                app.component(
                    'av-' + name,
                    vue.defineAsyncComponent(() => import(`<?php echo $xp_url ?>/vue-compos.php?name=av-${name}&ext=.js`))
                );
            });
        }
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
            let request_url = this.api_url;
            if (formData.has('api_url')) {
                request_url = formData.get('api_url');
                if (!request_url) {
                    // if api_url is empty, use default
                    request_url = this.api_url;
                }
                // formData.delete('api_url');
            }
            let data = null;
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
    from "<?php echo $xp_url ?>media/vue.esm-browser.prod.js";

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
</script>