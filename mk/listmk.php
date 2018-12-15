<?php
header('Content-Type:application/json');
$prodi = isset($_GET['prodi'])?$_GET['prodi']:'';
require_once('lib/nusoap.php');
$client = new nusoap_client('http://localhost:8080/9/akademik-service.php?wsdl',true);
$parIn = array('prodi'=>$prodi);
$parOut = $client->call('viewMkProdi', $parIn);
echo json_encode($parOut);
?>