console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="title">{{ title }}</h3>
    <h3 v-if="posts.length > 0">nb found: {{ posts.length }}</h3>
    <table>
        <tr v-for="p in posts">
        <td>
            <button class="update" @click.prevent="act_update(p)">update</button>
            <button class="delete" @click.prevent="act_delete(p)">delete</button>
        </td>
        <td>{{ p.ID }}</td>
            <td title="post_name"><input v-model="p.post_name"></td>
            <td title="post_title"><input v-model="p.post_title"></td>
            <td><textarea rows="10" cols="80" v-model="p.post_content"></textarea></td>
            <td>{{ p.post_date }}</td>
            <td title="post_status"><input v-model="p.post_status"></td>
            <td title="post_type"><input v-model="p.post_type"></td>
        </tr>
    </table>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    data() {
        return {
        }
    },
    computed: {
        posts() {
            return this.avroot.posts;
        },
    },
    methods: {
        async act_update(p) {
            console.log('act_update');
            console.log(p);
            // build the form data
            let inputs = {
                action: 'xpress',
                c: 'admin',
                m: 'posts_update',
                post_type: p.post_type,
                post_id: p.ID,
                post_name: p.post_name,
                post_title: p.post_title,
                post_content: p.post_content,
                post_status: p.post_status,
            }

            // send the form
            let r = await this.avroot.api(inputs);
            console.log(r);
            this.avroot.api_after(r, 'refresh_posts');
        },
        async act_delete(p) {
            console.log('act_delete');
            console.log(p);
            // build the form data
            let inputs = {
                action: 'xpress',
                c: 'admin',
                m: 'posts_delete',
                post_type: p.post_type,
                post_id: p.ID,
            }

            // send the form
            let r = await this.avroot.api(inputs);
            console.log(r);
            this.avroot.api_after(r, 'refresh_posts');
        },
    },
    created() {
    },
    mounted() {
    },
}
