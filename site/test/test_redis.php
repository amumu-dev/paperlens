<?php
$options = array(
    'namespace' => 'Application_',
    'servers'   => array(
       array('host' => '127.0.0.1', 'port' => 6379)
    )
);

require_once '../../api/lib/Rediska/library/Rediska.php';
$rediska = new Rediska($options);

$key = new Rediska_Key('keyName');
$key->setValue('value');
?>