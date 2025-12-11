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

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// 로그인
if ($method === 'POST' && $action === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => '이메일과 비밀번호를 입력하세요.']);
        exit;
    }

    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, email, password_hash FROM admin_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];

            echo json_encode([
                'success' => true,
                'email' => $user['email']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => '이메일 또는 비밀번호가 올바르지 않습니다.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => '로그인 처리 중 오류가 발생했습니다.']);
    }
    exit;
}

// 로그아웃
if ($method === 'POST' && $action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

// 세션 확인
if ($method === 'GET' && $action === 'check') {
    if (isset($_SESSION['admin_id'])) {
        echo json_encode([
            'authenticated' => true,
            'email' => $_SESSION['admin_email']
        ]);
    } else {
        echo json_encode(['authenticated' => false]);
    }
    exit;
}

// 관리자 계정 생성 (초기 설정용)
if ($method === 'POST' && $action === 'create_admin') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => '이메일과 비밀번호를 입력하세요.']);
        exit;
    }

    try {
        $pdo = getDBConnection();

        // 이미 존재하는지 확인
        $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => '이미 존재하는 이메일입니다.']);
            exit;
        }

        // 새 관리자 생성
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (email, password_hash) VALUES (?, ?)");
        $stmt->execute([$email, $password_hash]);

        echo json_encode([
            'success' => true,
            'message' => '관리자 계정이 생성되었습니다.'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => '계정 생성 중 오류가 발생했습니다.']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
?>
