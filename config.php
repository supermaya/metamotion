<?php
// 데이터베이스 연결 설정
// FastComet 호스팅 설정에 맞게 수정하세요

// 데이터베이스 연결 정보
define('DB_HOST', 'localhost');  // 데이터베이스 호스트 (일반적으로 localhost)
define('DB_NAME', 'playelga_metamotion');  // 데이터베이스 이름
define('DB_USER', 'playelga_metamotion');  // 데이터베이스 사용자명
define('DB_PASS', 'b0},&UdSc!a$0Dqe');  // 데이터베이스 비밀번호
define('DB_CHARSET', 'utf8mb4');

// 세션 설정
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 데이터베이스 연결 함수
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
}

// CORS 헤더는 각 API 파일에서 개별적으로 설정
// config.php에서는 헤더를 설정하지 않음
?>
