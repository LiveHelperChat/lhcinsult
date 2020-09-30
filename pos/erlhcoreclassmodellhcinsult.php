<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_insult";
$def->class = "erLhcoreClassModelLhcinsult";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['chat_id'] = new ezcPersistentObjectProperty();
$def->properties['chat_id']->columnName   = 'chat_id';
$def->properties['chat_id']->propertyName = 'chat_id';
$def->properties['chat_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['msg'] = new ezcPersistentObjectProperty();
$def->properties['msg']->columnName   = 'msg';
$def->properties['msg']->propertyName = 'msg';
$def->properties['msg']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['msg_id'] = new ezcPersistentObjectProperty();
$def->properties['msg_id']->columnName   = 'msg_id';
$def->properties['msg_id']->propertyName = 'msg_id';
$def->properties['msg_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['not_insult'] = new ezcPersistentObjectProperty();
$def->properties['not_insult']->columnName   = 'not_insult';
$def->properties['not_insult']->propertyName = 'not_insult';
$def->properties['not_insult']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['terminated'] = new ezcPersistentObjectProperty();
$def->properties['terminated']->columnName   = 'terminated';
$def->properties['terminated']->propertyName = 'terminated';
$def->properties['terminated']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['ctime'] = new ezcPersistentObjectProperty();
$def->properties['ctime']->columnName   = 'ctime';
$def->properties['ctime']->propertyName = 'ctime';
$def->properties['ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;

?>