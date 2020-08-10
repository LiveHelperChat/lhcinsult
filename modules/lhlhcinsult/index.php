<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcinsult/index.tpl.php');
$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcinsult/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult', 'Insult detection')
    )
);

?>