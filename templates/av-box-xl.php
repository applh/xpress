console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo box <?php echo $name ?>">
    <av-toolbar></av-toolbar>
    <av-form-builder></av-form-builder>
    <av-read-list></av-read-list>
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
