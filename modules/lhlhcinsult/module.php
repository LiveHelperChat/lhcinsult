<?php

$Module = array( "name" => "Live Helper Chat insult");

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('list')
);

$ViewList['options'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure')
);

$ViewList['insults'] = array(
    'params' => array(),
    'uparams' => array('action','id','terminated','chat_id','timefrom','timefrom_hours','timefrom_minutes','timeto','timeto_hours','timeto_minutes'),
    'functions' => array('list')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('list')
);

$ViewList['markasinsult'] = array(
    'params' => array('id'),
    'functions' => array('use')
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure Insult module');
$FunctionList['list'] = array('explain' => 'Allow operator to list insults');
$FunctionList['use'] = array('explain' => 'Allow operator to mark messages as insults');