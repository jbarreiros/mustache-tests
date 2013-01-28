<?php
/*
 * Simple app to play around with mustache templates.
 *
 * @todo add a refresh button that uses javascript to update page using the mustache templates
 */

require_once 'alaItem.php';
require '../Mustache/Autoloader.php';
Mustache_Autoloader::register();

try {
    $feed = simplexml_load_file('http://feeds.feedburner.com/alistapart/main?format=xml', 'alaItem', LIBXML_NOCDATA);
}
catch( Exception $e ) {}

$items = array(
    'issues'  => array(),
    'columns' => array(),
    'blog'    => array()
);

foreach( $feed->channel->item as $article ) {
    switch( $article->getArticleType() ) {
        case alaItem::TYPE_ISSUE:
            $issueNum = $article->getIssueNum();
            if( !isset($items['issues'][$issueNum]) ) {
                $items['issues'][$issueNum] = array( 'num' => $issueNum, 'articles' => array() );
            }
            $items['issues'][$issueNum]['articles'][] = $article->toTemplateArray();
            break;
        case alaItem::TYPE_COLUMN:
            $items['columns'][] = $article->toTemplateArray();
            break;
        case alaItem::TYPE_BLOG:
            $items['blog'][] = $article->toTemplateArray();
            break;
        default:
            break;
    }
}
// drop key names (issue number) so mustache will know to loop contents of "issues" key
$items['issues'] = array_values($items['issues']);

$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),
));
?>

<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Experiment: A List Apart Feed Reader</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <!--script src="js/vendor/modernizr-2.6.2.min.js"></script-->
</head>
<body>
    <header>
        <h1>A List Apart Feed Reader</h1>
        <p>Just futzing around with mustache.</p>
    </header>

    <div class="main">
        <?php echo $mustache->loadTemplate('issues')->render($items); ?>
        <?php echo $mustache->loadTemplate('columns')->render($items); ?>
        <?php echo $mustache->loadTemplate('blogs')->render($items); ?>
    </div>

    <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script-->
</body>
</html>