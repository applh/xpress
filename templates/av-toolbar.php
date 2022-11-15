console.log('compo module loaded: <?php echo $name ?>');

// add extra css 

// HTML template
let template = `
<div class="compo <?php echo $name ?>">
    <h3>Component <?php echo $name ?></h3>
    <ul>
        <li><a href="#">action1</a></li>
        <li><a href="#">action2</a></li>
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
    },
    created () {
    },
    mounted () {
    },
}
