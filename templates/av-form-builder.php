console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="af.title">{{ af.title }}</h3>
    <div>
        <form @submit.prevent="send_form($event)">
            <template v-for="input in inputs">
                <textarea v-if="input.type=='textarea'" :placeholder="input.placeholder" rows="10" v-model="input.value"></textarea>
                <input v-else :type="input.type" :placeholder="input.placeholder" v-model="input.value">
            </template>
            <button type="submit">{{ af.label_submit ?? 'SEND' }}</button>
            <pre class=".feedback">{{ feedback }}</pre>
        </form>
    </div>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    data() {
        return {
            active_menu: 'default',
            feedback: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    computed: {
        af() {
            return this.avroot.active_form;
        },
        inputs() {
            return this.avroot.active_form.inputs;
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
