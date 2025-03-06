<?php
// soal 1
function CekPalindrom($text): bool
{
    $result = strtolower(preg_replace('/[^a-z0-9]/', '', $text));
    return $result === strrev($result);
}
// echo CekPalindrom("katak")?'palindrom ':'bukan';

// soal 2
$program = [
    [
        "language" => "C",
        "appeared" => 1972,
        "created" => ["Dennis Ritchie"],
        "functional" => true,
        "object-oriented" => false,
        "relation" => [
            "influenced-by" => ["B", "ALGOL 68", "Assembly", "FORTRAN"],
            "influences" => ["C++", "Objective-C", "C#", "Java", "JavaScript", "PHP", "Go"]
        ]
    ]
];

// soal 3
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? null;

if (!$endpoint) {
    echo json_encode(["message" => "HELLO go developer"]);
    exit;
}
switch ($endpoint) {
    // soal 4 cek palindrome
    case 'palindrome':
        if (!isset($_GET['text'])) {
            http_response_code(400);
            echo json_encode(["message" => "kosong"]);
        } else {
            echo json_encode(["message" => CekPalindrom($_GET['text']) ? "PALINDROME" : "BUKAN"]);
        }
        break;
    // soal 5 CRUD DATA
    case 'language':
        $id = $_GET['id'] ?? null;
        if ($method === "GET") {
            if ($id !== null && isset($program[$id])) {
                echo json_encode($program[$id]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Not found"]);
            }
        } elseif ($method === "POST") {
            $input = json_decode(file_get_contents("php://input"), true);
            if ($inputData) {
                $program[] = $input;
                echo json_encode(["message" => "add data sukses", "data" => $input]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid JSON data"]);
            }
        } elseif ($method === "PATCH" && $id !== null && isset($program[$id])) {
            $input = json_decode(file_get_contents("php://input"), true);
            if ($input) {
                $program[$id] = array_merge($program[$id], $input);
                echo json_encode(["message" => "data sukses update", "data" => $program[$id]]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "ga ada data"]);
            }
        } elseif ($method === "DELETE" && $id !== null && isset($program[$id])) {
            array_splice($program, $id);
            echo json_encode(["message" => "hapus data berhasil"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
        break;
    case "languages":
        echo json_encode($program);
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "method tidak ada"]);
        break;
}
