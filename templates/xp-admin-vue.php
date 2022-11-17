<!-- VUEJS CONTAINER -->
<div id="appContainer" class="appBox"></div>

<!-- VUEJS TEMPLATE CSS-->
<style type="text/css">
    .appBox {
        background-color: #eee;
        padding: 2rem;
        padding-bottom: 8rem;
    }

    .appBox form {
        display: flex;
        flex-direction: column;
        max-width: 800px;
        flex-wrap: wrap;
    }

    .appBox form>* {
        margin: 0;
        padding: 0;
        width: 100%;
        display: block;
    }

    .appBox form .info>* {
        margin: 0.5rem 0;
        padding: 0.5rem;
        width: 100%;
        display: block;
    }

    .appBox button {
        padding: 1rem;
    }

    /* grid 2 columns 200px - */
    .box {
        display: grid;
        justify-items: stretch;
        align-items: stretch;
        justify-content: stretch;
        grid-template-columns: repeat(10, 1fr);
        grid-template-rows: repeat(10, 1fr);
    }

    .av-form-builder {
        grid-column: 3 / span 6;
        grid-row: 1 / span 2;
    }

    .av-read-list {
        grid-column: 3 / span 6;
        grid-row: 3 / span 6;
    }

    .appBox label span {
        padding: 0.5rem;
        display: inline-block;
    }

    .appBox nav ul {
        list-style: none;
        padding: 0;
    }

    .appBox nav a {
        text-decoration: none;
        display: inline-block;
        padding: 1rem;
        color: #000;
    }

    .appBox .menu-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        padding: 1rem;
        z-index: 100;
    }

    .appBox .menu-bottom a {
        color: #fff;
    }

    .appBox .popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 200;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .appBox .popup>* {
        width: 100%;
        display: block;
        text-align: center;
    }

    .popup .message {
        color:#000;
        background-color: #fff;
        padding: 1rem;
        margin: 1rem;
        border-radius: 0.5rem;
    }
</style>
<!-- JSON DATA -->
<template id="appJson">
    {
    "xp_url": "<?php echo $xp_url ?>",
    "api_key": "<?php echo $xpress_api_key ?>"
    }
</template>
<!-- VUEJS TEMPLATE -->
<template id="appTemplate" data-compos="box-sm box-md box-lg box-xl form-builder toolbar read-list">
    <section>
        <h1>XPress ({{ active_menu?.label }})</h1>
        <av-box-sm v-if="window_w < 800"></av-box-sm>
        <av-box-md v-else-if="window_w < 1200"></av-box-md>
        <av-box-lg v-else-if="window_w < 1600"></av-box-lg>
        <av-box-xl v-else></av-box-xl>
        <aside>
            <div class="menu-bottom">
                <div v-if="options_ui.show_api_config">
                    <form>
                        <div class="info">
                            <h3>api key</h3>
                            <input type="password" v-model="api_key">
                        </div>
                        <div class="info">
                            <h3>api url</h3>
                            <input type="text" v-model="api_url">
                        </div>
                    </form>
                </div>
                <nav>
                    <a href="#">XPress</a>
                    <label>
                        <span>api config</span>
                        <input type="checkbox" v-model="options_ui.show_api_config">
                    </label>
                    <label>
                        <span>show popup</span>
                        <input type="checkbox" v-model="options_ui.show_popup">
                    </label>
                </nav>
                <div class="popup" v-if="options_ui.show_popup">
                    <pre class="pad4 message">{{ message }}</pre>
                    <div>
                        <button @click.prevent="options_ui.show_popup=false">close</button>
                    </div>
                </div>

            </div>
        </aside>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module" src="<?php echo $xp_url ?>/media/xp-app.js">
</script>