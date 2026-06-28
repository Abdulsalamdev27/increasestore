<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function validate($value){
    return htmlspecialchars(strip_tags($value));
}

function uploadFile($file, $path){
    if(!isset($file) || $file['size'] == 0) return "";

    if(!file_exists($path)){
        mkdir($path, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = time() . "_" . rand(100,999) . "." . $ext;

    move_uploaded_file($file['tmp_name'], "$path/$newName");
    return "$path/$newName";
}

function generateQRCode($url, $savePath){
    if(!file_exists($savePath)){
        mkdir($savePath, 0777, true);
    }

    $filename = $savePath . "/" . time() . "_qrcode.png";

    QRcode::png($url, $filename, QR_ECLEVEL_L, 5);
    return $filename;
}

function insertRecord($conn, $table, $data){
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("','", array_map(function($v)use($conn){
        return mysqli_real_escape_string($conn, $v);
    }, array_values($data))) . "'";

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";

    if(mysqli_query($conn, $sql)){
        return mysqli_insert_id($conn);
    }
    return false;
}

function jsonResponse($status, $message, $data = null){
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}
