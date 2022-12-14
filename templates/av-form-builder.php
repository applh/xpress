console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="af?.title">{{ af?.title }}</h3>
    <div v-if="af?.description" v-html="af?.description"></div>
    <div>
        <form @submit.prevent="send_form($event)" enctype="multipart/form-data">
            <template v-for="input in inputs">
                <div class="info">
                    <label v-if="input.label">{{ input.label }}</label>

                    <textarea v-if="input.type=='textarea'" :placeholder="input.placeholder" rows="10" v-model="input.value"></textarea>
                    <input v-else-if="input.type=='file'" :type="input.type" :placeholder="input.placeholder" @change="updateFile(input, $event)">
                    <input v-else :type="input.type" :placeholder="input.placeholder" v-model="input.value">
                </div>
            </template>
            <div v-if="af?.before_submit_txt" v-html="af?.before_submit_txt"></div>
            <button type="submit">{{ af?.label_submit ?? 'SEND' }}</button>
            <pre class="feedback">{{ feedback }}</pre>
        </form>
    </div>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    props:['form_name'],
    data() {
        return {
            active_menu: 'home',
            feedback: '',
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    computed: {
        af() {
            let res = null;
            if (this.form_name) {
                res = this.avroot.forms[this.form_name];
            }
            else {
                res = this.avroot.active_form;
            }

            return res;
        },
        inputs() {
            let res = null;
            if (this.form_name) {
                res = this.avroot.forms[this.form_name]?.inputs ?? [];
            }
            else {
                res = this.avroot.active_form?.inputs ?? [];
            }
            return res;
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
            // check active form post_processing
            if (this.af.post_processing) {
                // call post_processing
                this.avroot.api_after(data, this.af.post_processing);
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
