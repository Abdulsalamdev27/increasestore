<?php
require_once __DIR__ . "/../helpers/jwt.php";

/**
 * -------------------------------------
 * ALWAYS RETURN JSON
 * -------------------------------------
 */
header("Content-Type: application/json");

/**
 * -------------------------------------
 * GET AUTHORIZATION HEADER
 * -------------------------------------
 */
$headers = getallheaders();

$authHeader = $headers['Authorization'] ?? '';

if (empty($authHeader)) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Authorization header missing"
    ]);

    exit;
}

/**
 * -------------------------------------
 * VALIDATE BEARER TOKEN
 * -------------------------------------
 */
if (strpos($authHeader, 'Bearer ') !== 0) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Invalid Authorization header"
    ]);

    exit;
}

/**
 * -------------------------------------
 * EXTRACT TOKEN
 * -------------------------------------
 */
$token = trim(substr($authHeader, 7));

if (empty($token)) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Token not provided"
    ]);

    exit;
}

/**
 * -------------------------------------
 * VERIFY JWT
 * -------------------------------------
 */
$user = verifyJWT($token, "SUPER_SECRET_KEY");

if (!$user || !is_array($user)) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Token expired or invalid"
    ]);

    exit;
}

/**
 * -------------------------------------
 * VALIDATE PAYLOAD
 * -------------------------------------
 */
if (!isset($user['admin_id'])) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Invalid token payload"
    ]);

    exit;
}

/**
 * -------------------------------------
 * NORMALIZE USER DATA
 * -------------------------------------
 */

$user['user_id'] = (int)$user['admin_id'];

$GLOBALS['authUser'] = [
    "user_id"    => (int)$user['admin_id'],
    "admin_id"   => (int)$user['admin_id'],
    "first_name" => $user['first_name'] ?? '',
    "last_name"  => $user['last_name'] ?? '',
    "email"      => $user['email'] ?? '',
    "phone"      => $user['phone'] ?? ''
];