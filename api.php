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

// 인증 확인 함수
function checkAuth() {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['error' => '인증이 필요합니다.']);
        exit;
    }
}

// 언어 파라미터 검증 함수
function validateLanguage($lang) {
    return in_array($lang, ['ko', 'en']) ? $lang : 'ko'; // 기본값은 한국어
}

$method = $_SERVER['REQUEST_METHOD'];
$type = $_GET['type'] ?? '';
$action = $_GET['action'] ?? '';
$lang = validateLanguage($_GET['lang'] ?? 'ko');

try {
    $pdo = getDBConnection();

    // Hero Slides 가져오기 (최대 3개) - 언어별
    if ($method === 'GET' && $type === 'hero') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                slide_order,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM hero_slides
            ORDER BY slide_order ASC
            LIMIT 3
        ");
        $stmt->execute();
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    // Solution Slides 가져오기 - 언어별
    if ($method === 'GET' && $type === 'solution') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                slide_order,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM solution_slides
            ORDER BY slide_order ASC
        ");
        $stmt->execute();
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    // Hero Slides 저장 (인증 필요, 최대 3개) - 양쪽 언어 모두 저장
    if ($method === 'POST' && $type === 'hero' && $action === 'save') {
        checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $slides = $data['slides'] ?? [];

        // 3개로 제한
        $slides = array_slice($slides, 0, 3);

        if (empty($slides)) {
            http_response_code(400);
            echo json_encode(['error' => '슬라이드 데이터가 없습니다.']);
            exit;
        }

        $pdo->beginTransaction();

        try {
            // 기존 데이터 삭제
            $pdo->exec("DELETE FROM hero_slides");

            // 새 데이터 삽입 (최대 3개, 양쪽 언어)
            $stmt = $pdo->prepare("
                INSERT INTO hero_slides (
                    slide_order,
                    title_ko, title_en,
                    description_ko, description_en,
                    image_url_ko, image_url_en
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                if ($index >= 3) break; // 추가 안전장치

                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Hero Slides가 저장되었습니다.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }

    // Solution Slides 저장 (인증 필요) - 양쪽 언어 모두 저장
    if ($method === 'POST' && $type === 'solution' && $action === 'save') {
        checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $slides = $data['slides'] ?? [];

        if (empty($slides)) {
            http_response_code(400);
            echo json_encode(['error' => '슬라이드 데이터가 없습니다.']);
            exit;
        }

        $pdo->beginTransaction();

        try {
            // 기존 데이터 삭제
            $pdo->exec("DELETE FROM solution_slides");

            // 새 데이터 삽입 (양쪽 언어)
            $stmt = $pdo->prepare("
                INSERT INTO solution_slides (
                    slide_order,
                    title_ko, title_en,
                    description_ko, description_en,
                    image_url_ko, image_url_en
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Solution Slides가 저장되었습니다.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }

    // Hero Slide 업데이트 (인증 필요) - 양쪽 언어
    if ($method === 'PUT' && $type === 'hero') {
        checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;

        $stmt = $pdo->prepare("
            UPDATE hero_slides
            SET title_ko = ?, title_en = ?,
                description_ko = ?, description_en = ?,
                image_url_ko = ?, image_url_en = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['title_ko'] ?? '',
            $data['title_en'] ?? '',
            $data['description_ko'] ?? '',
            $data['description_en'] ?? '',
            $data['image_url_ko'] ?? '',
            $data['image_url_en'] ?? '',
            $id
        ]);

        echo json_encode(['success' => true, 'message' => '슬라이드가 업데이트되었습니다.']);
        exit;
    }

    // Solution Slide 업데이트 (인증 필요) - 양쪽 언어
    if ($method === 'PUT' && $type === 'solution') {
        checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;

        $stmt = $pdo->prepare("
            UPDATE solution_slides
            SET title_ko = ?, title_en = ?,
                description_ko = ?, description_en = ?,
                image_url_ko = ?, image_url_en = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['title_ko'] ?? '',
            $data['title_en'] ?? '',
            $data['description_ko'] ?? '',
            $data['description_en'] ?? '',
            $data['image_url_ko'] ?? '',
            $data['image_url_en'] ?? '',
            $id
        ]);

        echo json_encode(['success' => true, 'message' => '슬라이드가 업데이트되었습니다.']);
        exit;
    }

    // Section Images 가져오기 - 언어별
    if ($method === 'GET' && $type === 'sections') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                section_key,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM section_images
            ORDER BY section_key ASC
        ");
        $stmt->execute();
        $sections = $stmt->fetchAll();
        echo json_encode(['sections' => $sections]);
        exit;
    }

    // Section Images 저장 (인증 필요) - 양쪽 언어
    if ($method === 'POST' && $type === 'sections' && $action === 'save') {
        checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $sections = $data['sections'] ?? [];

        if (empty($sections)) {
            http_response_code(400);
            echo json_encode(['error' => '섹션 데이터가 없습니다.']);
            exit;
        }

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO section_images (
                    section_key,
                    title_ko, title_en,
                    description_ko, description_en,
                    image_url_ko, image_url_en
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    title_ko = VALUES(title_ko),
                    title_en = VALUES(title_en),
                    description_ko = VALUES(description_ko),
                    description_en = VALUES(description_en),
                    image_url_ko = VALUES(image_url_ko),
                    image_url_en = VALUES(image_url_en)
            ");

            foreach ($sections as $section) {
                $stmt->execute([
                    $section['section_key'] ?? '',
                    $section['title_ko'] ?? '',
                    $section['title_en'] ?? '',
                    $section['description_ko'] ?? '',
                    $section['description_en'] ?? '',
                    $section['image_url_ko'] ?? '',
                    $section['image_url_en'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => '섹션 이미지가 저장되었습니다.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }

    // 관리자용 - 모든 언어 데이터 가져오기
    if ($method === 'GET' && $type === 'hero_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM hero_slides ORDER BY slide_order ASC LIMIT 3");
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    if ($method === 'GET' && $type === 'solution_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM solution_slides ORDER BY slide_order ASC");
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    if ($method === 'GET' && $type === 'sections_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM section_images ORDER BY section_key ASC");
        $sections = $stmt->fetchAll();
        echo json_encode(['sections' => $sections]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
