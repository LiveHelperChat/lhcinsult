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
    'uparams' => array(),
    'functions' => array('list')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('list')
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure Insult module');
$FunctionList['list'] = array('explain' => 'Allow operator to list insults');