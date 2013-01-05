<?php
require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

$template = <<<EOF
<label for="categories">Categories</label>
<select {{attrs}}>
    {{#optgroups}}
        {{#name}}
        <optgroup label="{{name}}">
            {{#options}}
            <option value="{{value}}">{{label}}</option>
            {{/options}}
        </optgroup>
        {{/name}}
        {{^name}}
            {{#options}}
            <option value="{{value}}">{{label}}</option>
            {{/options}}
        {{/name}}
    {{/optgroups}}
</select>
EOF;

$data = array(
    'attrs' => 'name="test" id="categories" class="alt"',
    'optgroups' => array(
        array(
            'name' => 'Colors',
            'options' => array(
                array( 'value' => 'red', 'label' => 'Red' ),
                array( 'value' => 'orange', 'label' => 'Orange' ),
                array( 'value' => 'yellow', 'label' => 'Yellow' ),
            )
        ),
        array(
            // this should never happen, but was curious how to make it not
            // render and empty optgroup
            'options' => array(
                array( 'value' => '8', 'label' => 'why not' )
            )
        ),
        array(
            'name' => 'Animals',
            'options' => array(
                array( 'value' => 'lion', 'label' => 'Lion' ),
                array( 'value' => 'tiger', 'label' => 'Tiger' ),
                array( 'value' => 'giraffe', 'label' => 'Giraffe' ),
            )
        )
    )
);

$mustache = new Mustache_Engine();
$tpl = $mustache->loadTemplate($template);
echo $tpl->render($data);
?>
