<?php
require_once '../vendor/autoload.php';

session_start();
require_once '../dbconn.php';
require_once '../authMiddleware.php';
date_default_timezone_set('Asia/Kolkata');

use Kreait\Firebase\Factory;

$headers = getallheaders();
$response = [];

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $accessToken = str_replace('Bearer ', '', $authHeader);
    $userId = authenticate($accessToken);

    $storage = (new Factory())
    ->withServiceAccount(__DIR__ . '/../firebase_credentials.json')
    ->createStorage();

    $bucket = $storage->getBucket();

    if(!empty($_FILES['files'])){
        header('Content-Type: application/json');

        foreach ($_FILES['files']['name'] as $key => $filename) {

            $uploadDate = date("Y-m-d H:i:s");
            $tmpPath = $_FILES['files']['tmp_name'][$key];
            $size = round($_FILES['files']['size'][$key]/1000000, 4);
            $type = explode("/", $_FILES['files']['type'][$key])[0];
            $storingType;

            switch($type){
                case 'image': $storingType = 'image'; break;
                case 'video': $storingType = 'video'; break;
                case 'audio': $storingType = 'audio'; break;
                case 'application': $storingType = 'document'; break;
                default: $storingType = 'other'; break;
            }

            $quota_query = "update `storage_quota` set `storage_used` = `storage_used` + $size, `{$storingType}_size` = `{$storingType}_size` + $size where `user_id` = '$userId'";
            $quota_result = mysqli_query($conn, $quota_query);
            $query = "insert into `uploads`(`user_id`, `upload_name`, `upload_date`, `type`, `size`) VALUES ('$userId','$filename','$uploadDate','$storingType','$size')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $insertedId = mysqli_insert_id($conn);
                $firebasePath = "$userId/$insertedId/$filename";
                
                try {
                    $bucket->upload(file_get_contents($tmpPath), [
                        'name' => $firebasePath
                    ]);
                    $response[] = ['success' => $firebasePath];
                } catch (Exception $e) {
                    $response[] = [
                        'error' => "Failed to upload to Firebase for file $filename",
                        'message' => $e->getMessage()
                    ];
                }
            }
        }
        echo json_encode($response);
    } else{
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['error'=>'No files received']);
    }
    
} else {
    http_response_code(401);
    echo json_encode(['error'=>'No access token provided.']);
    exit();
}
