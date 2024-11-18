<?php

session_start();
require_once '../dbconn.php';
require_once '../authMiddleware.php';

$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $accessToken = str_replace('Bearer ', '', $authHeader);
    $userId = authenticate($accessToken);

    $query = $headers['FromLocation'] ? "select * from `uploads` where `user_id` = '$userId' order by `id` desc limit 5" : "select * from `uploads` where `user_id` = '$userId' order by `id` desc";
    $result = mysqli_query($conn, $query);

    if($result){
        if(mysqli_num_rows($result) > 0){
            $uploads = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $uploads[] = $row;
            }
            echo json_encode(['uploads' => $uploads]);
        } else {
            echo json_encode(['warning' => 'No uploads found for this user.']);
        }
    } else {
        echo json_encode(['error' => 'Error fetching details']);
    }

} else {
    http_response_code(401);
    echo json_encode(['error' => 'No access token provided.']);
    exit();
}
