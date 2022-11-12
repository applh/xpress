<?php


// should output: /wp-content/plugins/xpress-main 
$xp_url = plugin_dir_url(__DIR__);

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
    .appBox form > * {
        margin: 0.5rem 0;
        padding: 0.5rem;
        width: 100%;
        display: block;
    }
</style>

<!-- VUEJS TEMPLATE -->
<template id="appTemplate" data-compos="box-sm box-md box-lg box-xl">
    <section>
        <h1>XPress</h1>
        <p><?php echo "($xp_url)" ?></p>
        <p class="pad4">{{ message }}</p>
        <av-box-md></av-box-md>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module">
    console.log('hello');

// store my reactive data
let appData = {
    api_url: '<?php echo $xp_url?>/api.php',
    window_w: window.innerWidth,
    window_h: window.innerHeight,
    message: 'Vue is everywhere!'
}

let created = function () {
    console.log('created');

    // WARNING: REGISTER COMPONENTS BEFORE MOUNTING
    let compos = appTemplate?.getAttribute("data-compos");
    if (compos) {
        compos = compos.split(' ');
        compos.forEach(function (name) {
            app.component(
                'av-' + name,
                vue.defineAsyncComponent(() => import(`<?php echo $xp_url ?>/vue-compos.php?name=av-${name}&ext=.js`))
            );
        });
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
        m: 'stat',
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
        // send request
        let response = await fetch(this.api_url, {
            method: 'POST',
            body: formData
        });

        let data = null;
        try {
            data = await response.json();
            console.log(data);
        } catch (e) {
            console.log(e);
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