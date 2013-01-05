<?php
require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

$template = <<<EOF
<p>Hi {{name}}!</p>

{{< partial}}
EOF;

$data = array(
    'name' => 'Frasier Crane',
    'details' => array(
        'street-address' => '84 Beacon St.',
        'city'           => 'Boston',
        'state'          => 'MA',
        'zip'            => '02108'
    ),
    'override-details' => array(
        'zip'           => '02108-3421'
    )
);

$mustache = new Mustache_Engine(array(
    'partials_loader' => new Mustache_Loader_FilesystemLoader( dirname(__FILE__).'/partials' ),
));
$tpl = $mustache->loadTemplate($template);
echo $tpl->render($data);
?>
