<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcinsult/options.tpl.php');

$lhciOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');

$data = (array)$lhciOptions->data;

if ( isset($_POST['StoreOptions']) ) {

    $definition = array(
        'enabled' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'enabled_img' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'query_attr' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'attr_loc' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'host' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'host_img' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'enabled' ) && $form->enabled == true ) {
        $data['enabled'] = 1;
    } else {
        $data['enabled'] = 0;
    }

    if ( $form->hasValidData( 'enabled_img' ) && $form->enabled_img == true ) {
        $data['enabled_img'] = 1;
    } else {
        $data['enabled_img'] = 0;
    }

    if ($form->hasValidData( 'query_attr' )) {
        $data['query_attr'] = $form->query_attr;
    } else {
        $data['query_attr'] = 'x';
    }

    if ($form->hasValidData( 'attr_loc' )) {
        $data['attr_loc'] = $form->attr_loc;
    } else {
        $data['attr_loc'] = '';
    }

    if ($form->hasValidData( 'host' )) {
        $data['host'] = $form->host;
    } else {
        $data['host'] = '';
    }

    if ($form->hasValidData( 'host_img' )) {
        $data['host_img'] = $form->host_img;
    } else {
        $data['host_img'] = '';
    }

    $lhciOptions->explain = '';
    $lhciOptions->type = 0;
    $lhciOptions->hidden = 1;
    $lhciOptions->identifier = 'lhcinsult_options';
    $lhciOptions->value = serialize($data);
    $lhciOptions->saveThis();

    $tpl->set('updated','done');
}

$tpl->set('lhci_options',$data);
$tpl->set('tab','');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcinsult/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult', 'Insult detection')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult', 'Options')
    )
);

?>