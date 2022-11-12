<?php


// should output: /wp-content/plugins/xpress-main 
$xp_url = plugin_dir_url(__DIR__);

echo "Hello XPress ($xp_url)";

?>
<!-- VUEJS CONTAINER -->
<div id="appContainer"></div>

<!-- VUEJS TEMPLATE -->
<template id="appTemplate" data-compos="box-sm box-md box-lg box-xl">
    <section>
        <p class="pad4">{{ message }}</p>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module">
    console.log('hello');

// store my reactive data
let appData = {
    api_url: '',
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
                vue.defineAsyncComponent(() => import(`/vue/av-${name}.js`))
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