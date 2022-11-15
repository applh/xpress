console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3>Component <?php echo $name ?></h3>
    <div>
        <form @submit.prevent="send_form($event)">
            <template v-for="input in inputs">
                <textarea v-if="input.type=='textarea'" rows="10" v-model="input.value"></textarea>
                <input v-else :type="input.type" :placeholder="input.placeholder" v-model="input.value">
            </template>
            <button type="submit">Send</button>
            <pre>{{ feedback }}</pre>
        </form>
    </div>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    data() {
        return {
            inputs: [
                {name: 'api_url', value: '/wp-admin/admin-ajax.php'},
                {name: 'api_key', value: '', 'type': 'password'},
                {name: 'action', value: 'xpress'}, // NEEDED BY WP admin-ajax.php
                {name: 'c', value: 'public'},
                {name: 'm', value: 'test'},
                {name: 'code', value: '', 'type': 'textarea'},
            ],
            feedback: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    methods: {
        async send_form(event) {
            this.feedback = 'sending...';
            
            console.log('send_form');
            console.log(event);
            let inputs = {};

            // copy this.inputs to inputs
            this.inputs.forEach(function (input) {
                inputs[input.name] = input.value;
            });

            let data = await this.avroot.api(inputs);
            console.log(data);
            if (data.feedback) {
                this.feedback = data.feedback;
            }
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
