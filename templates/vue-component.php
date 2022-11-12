<?php

header('Content-Type: application/javascript');

// get name
$name = $_REQUEST['name'] ?? '';

?>

console.log('compo module loaded: <?php echo $name ?>');

// add extra css 


let template = `
<div class="compo">
    <h3>Component <?php echo $name ?></h3>
    <div>
        <form @submit.prevent="send_form($event)">
            <input type="text" required placeholder="api_key" v-model="in_api_key">
            <textarea required placeholder="enter your code" v-model="in_code" rows="10"></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    data() {
        return {
            in_code: '',
            in_api_key: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    methods: {
        send_form(event) {
            console.log('send_form');
            console.log(event);
            let inputs = {
                m: 'test',
                code: this.in_code,
                api_key: this.in_api_key,
            };
            this.avroot.api(inputs);
        },
        test(msg = '') {
            console.log('HELLO FROM COMPO: ' + msg);
        }
    },
    created () {
        console.log('COMPO CREATED <?php echo $name ?>');
        this.test('created');
        this.avroot.test('created');
    },
}
