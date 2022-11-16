<!-- VUEJS CONTAINER -->
<div id="appContainer" class="appBox"></div>

<!-- VUEJS TEMPLATE CSS-->
<style type="text/css">
    .appBox {
        background-color: #eee;
        padding: 2rem;
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
        <p class="pad4">{{ message }}</p>
        <aside>
            <div>
                <label>
                    <span>api config</span>
                    <input type="checkbox" v-model="options_ui.show_api_config">
                </label>
            </div>
            <div v-if="options_ui.show_api_config">
                <form>
                    <h3>api url</h3>
                    <input type="text" v-model="api_url">
                    <h3>api key</h3>
                    <input type="password" v-model="api_key">
                </form>
            </div>
        </aside>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module" src="<?php echo $xp_url ?>/media/xp-app.js">
</script>