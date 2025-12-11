<?php
require_once 'config.php';

// CORS 헤더 설정
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// OPTIONS 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 인증 확인
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => '인증이 필요합니다.']);
    exit;
}

// 업로드 디렉토리 설정
$uploadDir = 'uploads/';

// 디렉토리가 없으면 생성
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 파일이 업로드되었는지 확인
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => '파일 업로드에 실패했습니다.']);
        exit;
    }

    $file = $_FILES['image'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // 파일 확장자 확인
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        http_response_code(400);
        echo json_encode(['error' => '허용되지 않는 파일 형식입니다. (jpg, jpeg, png, gif, webp만 가능)']);
        exit;
    }

    // 파일 크기 확인 (10MB 제한)
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    if ($fileSize > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => '파일 크기가 너무 큽니다. (최대 10MB)']);
        exit;
    }

    // 이미지 파일인지 확인
    $imageInfo = getimagesize($fileTmpName);
    if ($imageInfo === false) {
        http_response_code(400);
        echo json_encode(['error' => '유효한 이미지 파일이 아닙니다.']);
        exit;
    }

    // 고유한 파일명 생성
    $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;
    $targetPath = $uploadDir . $uniqueFileName;

    // 파일 이동
    if (move_uploaded_file($fileTmpName, $targetPath)) {
        // 전체 URL 생성
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']);
        $fileUrl = $baseUrl . '/' . $targetPath;

        echo json_encode([
            'success' => true,
            'url' => $fileUrl,
            'filename' => $uniqueFileName
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '파일 저장에 실패했습니다.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
