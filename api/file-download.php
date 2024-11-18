<?php

require_once '../vendor/autoload.php';

session_start();
require_once '../dbconn.php';
require_once '../authMiddleware.php';

use Kreait\Firebase\Factory;

$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $accessToken = str_replace('Bearer ', '', $authHeader);
    $userId = authenticate($accessToken);

    $storage = (new Factory())
    ->withServiceAccount(__DIR__ . '/../firebase_credentials.json')
    ->createStorage();

    $bucket = $storage->getBucket();

    $name = $_GET['name'] ?? null;
    $id = $_GET['id'] ?? null;

    
    if($name){
        $filePath = "$userId/$id/$name";
        $object = $bucket->object($filePath);
        $expiresAt = new DateTime('+1 hour');
        $downloadUrl = $object->signedUrl($expiresAt);
    
        echo json_encode(['downloadUrl' => $downloadUrl]);
    } else {
        echo json_encode(['error' => 'File path not provided']);
    }

} else {
    http_response_code(401);
    echo json_encode(['error'=>'No access token provided.']);
    exit();
}