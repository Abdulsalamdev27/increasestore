<?php
require "../helpers/jwt.php";

header("Content-Type: application/json");

if (!isset($_COOKIE['refresh_token'])) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "No refresh token"]);
    exit;
}

$refreshToken = $_COOKIE['refresh_token'];
$data = verifyJWT($refreshToken, "REFRESH_SECRET_KEY");

if (!$data) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "Invalid refresh token"]);
    exit;
}

$newAccessToken = generateJWT([
    "user_id" => $data['user_id'],
    "exp" => time() + (60 * 15)
], "SUPER_SECRET_KEY");

echo json_encode([
    "status" => true,
    "token" => $newAccessToken
]);
