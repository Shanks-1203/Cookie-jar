<?php
session_start();
include_once 'dbconn.php';

if (isset($_GET['uid']) && isset($_GET['email']) && isset($_GET['username']) && isset($_GET['profilePic']) && isset($_GET['accessToken'])) {
    $userId = $_GET['uid'];
    $userEmail = $_GET['email'];
    $username = $_GET['username'];
    $profilePic = $_GET['profilePic'];
    $accessToken = $_GET['accessToken'];

    $_SESSION['user_email'] = $userEmail;
    $_SESSION['username'] = $username;
    $_SESSION['profile_pic'] = $profilePic;
    $_SESSION['access_token'] = $accessToken;

    $query = "select * from `storage_quota` where `user_id`='$userId'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        $query = "insert into `storage_quota` (`user_id`) values ('$userId')";
        mysqli_query($conn, $query);
    }

    header('Location: index.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
