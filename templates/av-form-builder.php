console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="af?.title">{{ af?.title }}</h3>
    <div>
        <form @submit.prevent="send_form($event)" enctype="multipart/form-data">
            <template v-for="input in inputs">
                <textarea v-if="input.type=='textarea'" :placeholder="input.placeholder" rows="10" v-model="input.value"></textarea>
                <input v-else-if="input.type=='file'" :type="input.type" :placeholder="input.placeholder" @change="updateFile(input, $event)">
                <input v-else :type="input.type" :placeholder="input.placeholder" v-model="input.value">
            </template>
            <button type="submit">{{ af?.label_submit ?? 'SEND' }}</button>
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
            active_menu: 'home',
            feedback: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    computed: {
        af() {
            return this.avroot.active_form;
        },
        inputs() {
            return this.avroot.active_form?.inputs ?? [];
        }
    },
    methods: {
        updateFile(input, e) {
            input.files = e.target.files || e.dataTransfer.files;
            console.log(input.files);
        },
        async send_form(event) {
            this.feedback = 'sending...';
            
            console.log('send_form');
            // console.log(event.target);
            // FIXME: NOT WORKING WITH VUEJS ???
            let fd = new FormData(event.target);

            // copy inputs to fd
            this.inputs.forEach(input => {
                // if input is file
                if (input.files) {
                    for (let i = 0; i < input.files.length; i++) {
                        // FIXME: SHOULD UPDATE input name ? 
                        fd.append(input.name, input.files[i]);
                    }
                } else {
                    fd.append(input.name, input.value);
                }
            });

            // debug 
            // Display the key/value pairs
            // for (const pair of fd.entries()) {
            //     console.log(`${pair[0]}, ${pair[1]}`);
            // }

            let data = await this.avroot.api({}, fd);
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
