<?php
require_once '../vendor/autoload.php';

session_start();
require_once '../dbconn.php';
require_once '../authMiddleware.php';
header('Content-Type: application/json');

use Kreait\Firebase\Factory;

$headers = getallheaders();

if(isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $accessToken = str_replace('Bearer ', '', $authHeader);
    $userId = authenticate($accessToken);

    $storage = (new Factory())
    ->withServiceAccount(__DIR__ . '/../firebase_credentials.json')
    ->createStorage();

    $bucket = $storage->getBucket();

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_DELETE);
    
        $name = $_DELETE['name'] ?? null;
        $size = $_DELETE['size'] ?? null;
        $type = $_DELETE['type'] ?? null;
        $id = $_DELETE['id'] ?? null;
        $filePath = "$userId/$id/$name";
        
        if ($name && $id) {

            $query = "delete from `uploads` where `id`='$id'";
            $result = mysqli_query($conn, $query);
            $quota_query = "update `storage_quota` set `storage_used` = `storage_used` - $size, `{$type}_size` = `{$type}_size` - $size where `user_id` = '$userId'";
            $quota_result = mysqli_query($conn, $quota_query);

            if($result && $quota_result){
                try{
                    $object = $bucket->object($filePath);
        
                    if ($object->exists()) {
                        $object->delete();
                        echo json_encode(['success' => $filePath]);
                        exit();
                    } else {
                        echo json_encode(['error' => "File not found " + $filePath]);
                        exit();
                    }
                } catch (Exception $e) {
                    echo json_encode(['error' => "Error deleting file: " . $e->getMessage()]);
                    exit();
                }
            }
            
        } else {
            echo json_encode(['error' => 'name or size not present']);
            exit();
        }
    }

} else {
    http_response_code(401);
    echo json_encode(['error' => 'No access token provided.']);
    exit();
}
