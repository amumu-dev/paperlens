<?php
$options = array(
    'namespace' => 'Application_',
    'servers'   => array(
       array('host' => '127.0.0.1', 'port' => 6379),
       array('host' => '127.0.0.1', 'port' => 6380)
    )
);

require_once '../../lib/Rediska/library/Rediska.php';
$rediska = new Rediska($options);

$key = new Rediska_Key('keyName');
$key->setValue('value');
?>