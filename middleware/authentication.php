<?php


require_once __DIR__ . "/../config/dbconn.php";
require_once __DIR__ . "/../e-api/helpers/jwt.php";

/**
 * ------------------------------------
 * 🔐 SHARED JWT VERIFIER
 * ------------------------------------
 */
function jwtVerifyFromRequest()
{
    $secret = $_ENV['JWT_SECRET'] ?? "SUPER_SECRET_KEY";
    $token  = null;

    // 1️⃣ Authorization header (SERVER SAFE)
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $token = str_replace("Bearer ", "", $_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }

    // 2️⃣ Cookie fallback
    if (!$token && isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
    }

    if (!$token) {
        return null;
    }

    // 3️⃣ Verify JWT
    return verifyJWT($token, $secret);
}

/**
 * ------------------------------------
 * 🔐 PAGE GUARD (Redirects)
 * ------------------------------------
 */
function jwtPageGuard()
{
    $user = jwtVerifyFromRequest();

    if (!$user) {
        setcookie("auth_token", "", time() - 3600, "/");
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }

    return $user;
}

/**
 * ------------------------------------
 * 🔐 API GUARD (JSON response)
 * ------------------------------------
 */
function jwtApiGuard()
{
    $user = jwtVerifyFromRequest();

    if (!$user) {
        http_response_code(401);
        echo json_encode([
            "status"  => false,
            "message" => "Unauthorized"
        ]);
        exit;
    }

    return $user;
}



