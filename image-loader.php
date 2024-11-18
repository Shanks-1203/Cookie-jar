<?php
session_start();

if (isset($_SESSION['profile_pic'])) {
    $imageURL = $_SESSION['profile_pic'];

    header("Content-Type: image/jpeg");
    $imageContent = file_get_contents($imageURL);
    
    if ($imageContent) {
        echo $imageContent;
    } else {
        header("X-Fallback-Image: true");
        readfile("./Images/user.png");
    }
} else {
    header("X-Fallback-Image: true");
    readfile("./Images/user.png");
}
