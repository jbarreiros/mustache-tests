<?php
require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

$template = <<<EOF
<fieldset{{{fieldset.attrs}}}>
    <legend>{{fieldset.legend}}</legend>

    {{#options}}
    <label for="{{id}}">
        <input type="checkbox" id="{{id}}" name="{{name}}" value="{{value}}"{{#checked}} checked="checked"{{/checked}}>
        {{label}}
    </label>
    {{/options}}
</fieldset>
EOF;

$data = array(
    'fieldset' => array(
        'attrs' => ' id="colors"',
        'legend' => 'Favorite Color'
    ),
    'options' => array(
        array( 'id' => 'c1', 'name' => 'red', 'value' => 'red', 'label' => 'Red' ),
        array( 'id' => 'c2', 'name' => 'orange', 'value' => 'orange', 'label' => 'Orange', 'checked' => true ),
        array( 'id' => 'c3', 'name' => 'yellow', 'value' => 'yellow', 'label' => 'Yellow' ),
    )
);

$mustache = new Mustache_Engine();
$tpl = $mustache->loadTemplate($template);
echo $tpl->render($data);
?>