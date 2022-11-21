console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="title">{{ title }}</h3>
    <h3>nb found: {{ posts.length }}</h3>
    <table>
        <tr v-for="p in posts">
            <td>{{ p.ID }}</td>
            <td>{{ p.post_title }}</td>
            <td><textarea rows="10" cols="80">{{ p.post_content }}</textarea></td>
            <td>{{ p.post_status }}</td>
            <td>
                <button class="update" @click.prevent="act_update(p)">update</button>
                <button class="delete" @click.prevent="act_delete(p)">delete</button>
            </td>
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
            // this.avroot.act_update(p);
        },
        async act_delete(p) {
            console.log('act_delete');
            console.log(p);  
            // this.avroot.act_delete(p);
        },
    },
    created () {
    },
    mounted () {
    },
}
