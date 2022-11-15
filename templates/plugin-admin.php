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

    /* grid 2 columns 200px - */
    .av-box-md,
    .av-box-lg,
    .av-box-xl {
        display: grid;
        grid-template-columns: 200px 1fr;
    }

    .appBox label span {
        padding: 0.5rem;
        display: inline-block;
    }
</style>

<template id="appJson">
    {
    "xp_url": "<?php echo $xp_url ?>",
    "api_key": "<?php echo $xpress_api_key ?>"
    }
</template>

<!-- VUEJS TEMPLATE -->
<template id="appTemplate" data-compos="box-sm box-md box-lg box-xl form-builder toolbar">
    <section>
        <h1>XPress ({{ active_menu?.label }})</h1>
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
        <av-box-sm v-if="window_w < 800"></av-box-sm>
        <av-box-md v-else-if="window_w < 1200"></av-box-md>
        <av-box-lg v-else-if="window_w < 1600"></av-box-lg>
        <av-box-xl v-else></av-box-xl>
        <p class="pad4">{{ message }}</p>
    </section>
</template>

<!-- VUEJS INIT -->
<script type="module" src="<?php echo $xp_url ?>/media/xp-app.js">
</script>