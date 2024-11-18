<?php

session_start();
require_once '../dbconn.php';
require_once '../authMiddleware.php';

$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $accessToken = str_replace('Bearer ', '', $authHeader);
    $userId = authenticate($accessToken);
    
    $quota_query = "select * from `storage_quota` where `user_id` = '$userId'";
    $quota_result = mysqli_query($conn, $quota_query);

    
    if ($row = mysqli_fetch_assoc($quota_result)) {
        header('Content-Type: application/json');

        $number = ["document"=>0, "image"=>0, "video"=>0, "audio"=>0, "other"=>0];
    
        $file_query = "select * from `uploads` where `user_id` = '$userId'";
        $file_result = mysqli_query($conn, $file_query);
    
        while($file_row = mysqli_fetch_assoc($file_result)){

            $file_type = $file_row['type'];

            if (isset($number[$file_type])) {
                $number[$file_type]++;
            }
        }
        
        $response = array_merge($row, $number);

        echo json_encode($response);
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'No storage quota found for this user.']);
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'No access token provided.']);
    exit();
}
