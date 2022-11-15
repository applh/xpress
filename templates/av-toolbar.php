console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <ul>
        <li><a href="#" @click="menu('default')">default</a></li>
        <li><a href="#" @click="menu('contact')">contact</a></li>
        <li><a href="#">action3</a></li>
        <li><a href="#">action4</a></li>
        <li><a href="#">action5</a></li>
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
    methods: {
        menu(name) {
            console.log('menu: ' + name);
            this.avroot.active_menu = name;
            this.avroot.active_form = this.avroot.forms[name];
        }
    },
    created () {
    },
    mounted () {
    },
}
