console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo">
    <h3>Component <?php echo $name ?></h3>
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
