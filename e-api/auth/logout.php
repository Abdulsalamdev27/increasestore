<?php
setcookie("refresh_token", "", time() - 3600, "/", "", false, true);

echo json_encode([
    "status" => true,
    "message" => "Logged out successfully"
]);
