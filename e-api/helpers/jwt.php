<?php

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function generateJWT(array $payload, $secret) {
    $header = ['typ' => 'JWT', 'alg' => 'HS256'];

    $base64Header  = base64url_encode(json_encode($header));
    $base64Payload = base64url_encode(json_encode($payload));

    $signature = hash_hmac(
        'sha256',
        "$base64Header.$base64Payload",
        $secret,
        true
    );

    return "$base64Header.$base64Payload." . base64url_encode($signature);
}

function verifyJWT($token, $secret) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    [$header, $payload, $signature] = $parts;

    $expected = base64url_encode(
        hash_hmac('sha256', "$header.$payload", $secret, true)
    );

    if (!hash_equals($expected, $signature)) return false;

    $data = json_decode(base64url_decode($payload), true);
    if (!$data) return false;

    if (isset($data['exp']) && $data['exp'] < time()) {
        return false;
    }

    return $data; // ✅ ARRAY
}
