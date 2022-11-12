<?php

header('Content-Type: application/javascript');

// get name
$name = $_REQUEST['name'] ?? '';

?>

console.log('compo module loaded: <?php echo $name ?>');

let template = `
<div class="compo">
    <h3>Component <?php echo $name ?></h3>
</div>
`;

export default {
    template,
    inject: ['avroot'],
    data() {
        return {
            message: 'Hello from compo <?php echo $name ?>',
        }
    },
    methods: {
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
