<?php
require_once 'vendor/autoload.php';

use Kreait\Firebase\Factory;

function authenticate($accessToken) {
    $firebase = (new Factory)->withServiceAccount(__DIR__ . '/firebase_credentials.json')->createAuth();

    try {
        $verifiedIdToken = $firebase->verifyIdToken($accessToken);
        return $verifiedIdToken->claims()->get('sub');
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        header("Location: login.php");
        exit();
    } catch (\Throwable $e) {
        header("Location: login.php");
        exit();
    }
}
