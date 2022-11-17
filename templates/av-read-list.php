console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3 v-if="title">{{ title }}</h3>

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
    },
    created () {
    },
    mounted () {
    },
}
