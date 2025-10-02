<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcinsult/options.tpl.php');

$lhciOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');

$data = (array)$lhciOptions->data;

if ( isset($_POST['testTextAction']) ) {
    $lhcinsultOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');
    try {
        $response = erLhcoreClassLhcinsultWorker::isInsult($_POST['testText'], $lhcinsultOptions->data);
        $tpl->set('validation_output', $response);
    } catch (Exception $e) {
        $Errors[] = $e->getMessage();
    }

    if (!empty($Errors)) {
        $tpl->set('validation_output', $Errors);
    }
}


if ( isset($_POST['testImage']) ) {
    $Errors = [];
    if ( isset($_FILES["UserPhoto"]) && is_uploaded_file($_FILES["UserPhoto"]["tmp_name"]) && $_FILES["UserPhoto"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('UserPhoto') ) {
        $Errors = array();
        $dir = 'var/tmpfiles/';
        $file = qqFileUploader::upload($_FILES,'UserPhoto',$dir);
        try {
            if ( !empty($file["errors"]) ) {
                foreach ($file["errors"] as $err) {
                    $Errors[] = $err;
                }
            } else {
                $lhcinsultOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');
                $response = erLhcoreClassLhcinsultWorker::isNudeRestAPI($file["data"]["filename"],  $file["data"]["dir"] . '/' . $file["data"]["filename"] , -1, $data['host_img'], 1);
                $tpl->set('validation_output', $response);
            }
        } catch (Exception $e) {
            $Errors[] = $e->getMessage();
        } finally {
            if (file_exists($file["data"]["dir"] . '/' . $file["data"]["filename"])) {
                unlink( $file["data"]["dir"] . '/' . $file["data"]["filename"]);
            }
        }
    } else {
        $Errors = ['File not uploaded.'];
    }
    if (!empty($Errors)) {
        $tpl->set('validation_output', $Errors);
    }
}

if ( isset($_POST['StoreOptions']) ) {

    $definition = array(
        'enabled' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'enabled_img' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'auto_enable' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'disable_in_msg' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'disable_in_img' => new ezcInputFormDefinitionElement(
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
        'provider' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'detoxify' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'host_img' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'safe_comb' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'report_email_in' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
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
    if ($form->hasValidData( 'detoxify' )) {
        $data['detoxify'] = $form->detoxify;
    } else {
        $data['detoxify'] = '';
    }
    
    if ($form->hasValidData( 'provider' )) {
        $data['provider'] = $form->provider;
    } else {
        $data['provider'] = '';
    }

    if ($form->hasValidData( 'safe_comb' )) {
        $data['safe_comb'] = $form->safe_comb;
    } else {
        $data['safe_comb'] = '';
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

    // Failover settings
    if ( $form->hasValidData( 'auto_enable' ) && $form->auto_enable == true ) {
        $data['auto_enable'] = 1;
    } else {
        $data['auto_enable'] = 0;
    }

    if ( $form->hasValidData( 'disable_in_msg' ) && $form->disable_in_msg == true ) {
        $data['disable_in_msg'] = 1;
    } else {
        $data['disable_in_msg'] = 0;
    }

    if ( $form->hasValidData( 'disable_in_img' ) && $form->disable_in_img == true ) {
        $data['disable_in_img'] = 1;
    } else {
        $data['disable_in_img'] = 0;
    }

    if ( $form->hasValidData( 'report_email_in' )) {
        $data['report_email_in'] = $form->report_email_in ;
    } else {
        $data['report_email_in'] = '';
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