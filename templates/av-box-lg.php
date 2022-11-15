console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <av-toolbar></av-toolbar>
    <av-form-builder></av-form-builder>
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
