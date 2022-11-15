console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <ul>
        <li v-for="item in am">
            <a href="#" @click="menu(item.value)">{{ item.label }}</a>
        </li>
    </ul>
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
        am() {
            return this.avroot.menus;
        }
    },
    methods: {
        menu(name) {
            console.log('menu: ' + name);
            // update menu             
            this.avroot.active_menu = this.avroot.menus[name];
            this.avroot.active_form = this.avroot.forms[name];
        }
    },
    created () {
    },
    mounted () {
    },
}
