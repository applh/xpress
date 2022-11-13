console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo">
    <h3>Component <?php echo $name ?></h3>
    <div>
        <form @submit.prevent="send_form($event)">
            <input type="text" required placeholder="api_key" v-model="in_api_key">
            <input type="text" required placeholder="class" v-model="in_c">
            <input type="text" required placeholder="method" v-model="in_m">
            <textarea required placeholder="enter your code" v-model="in_code" rows="10"></textarea>
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
            feedback: '',
            in_c:'public',
            in_m:'test',
            in_code: '',
            in_api_key: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    methods: {
        async send_form(event) {
            this.feedback = 'sending...';
            
            console.log('send_form');
            console.log(event);
            let inputs = {
                api_key: this.in_api_key,
                c: this.in_c,
                m: this.in_m,
                code: this.in_code,
            };
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
