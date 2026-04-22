<?php
require_once 'config.php';

// CORS 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 허용 도메인 화이트리스트
$allowedOrigins = [
    'https://metamotion.io',
    'https://www.metamotion.io',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

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
    return in_array($lang, ['ko', 'en', 'cn']) ? $lang : 'ko'; // 기본값은 한국어
}

$method = $_SERVER['REQUEST_METHOD'];
$type = $_GET['type'] ?? '';
$action = $_GET['action'] ?? '';
$lang = validateLanguage($_GET['lang'] ?? 'ko');

try {
    $pdo = getDBConnection();

    // ── 갤러리 이미지 목록 ──────────────────────────────────────────
    if ($method === 'GET' && $type === 'gallery') {
        checkAuth();
        $uploadDir = 'uploads/';
        $protocol  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host      = $_SERVER['HTTP_HOST'];
        $basePath  = rtrim(dirname($_SERVER['PHP_SELF']), '/');
        $baseUrl   = $protocol . '://' . $host . $basePath;
        $images    = [];
        if (is_dir($uploadDir)) {
            $exts = ['jpg','jpeg','png','gif','webp'];
            $files = scandir($uploadDir);
            rsort($files); // 최신순
            foreach ($files as $f) {
                if ($f === '.' || $f === '..') continue;
                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                if (!in_array($ext, $exts)) continue;
                $images[] = [
                    'filename' => $f,
                    'url'      => $baseUrl . '/' . $uploadDir . $f,
                    'size'     => filesize($uploadDir . $f),
                    'mtime'    => filemtime($uploadDir . $f),
                ];
            }
        }
        echo json_encode(['images' => $images]);
        exit;
    }
    // ────────────────────────────────────────────────────────────────

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
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                if ($index >= 3) break; // 추가 안전장치

                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['title_cn'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['description_cn'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? '',
                    $slide['image_url_cn'] ?? ''
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
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['title_cn'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['description_cn'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? '',
                    $slide['image_url_cn'] ?? ''
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

    // Avatar Slides 저장 (인증 필요) - 3개 언어 모두 저장
    if ($method === 'POST' && $type === 'avatar' && $action === 'save') {
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
            $pdo->exec("DELETE FROM avatar_slides");

            // 새 데이터 삽입 (한국어 / 영어 / 중국어)
            $stmt = $pdo->prepare("
                INSERT INTO avatar_slides (
                    slide_order,
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['title_cn'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['description_cn'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? '',
                    $slide['image_url_cn'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Avatar Slides가 저장되었습니다.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }

    // Content Slides 저장 (인증 필요) - 3개 언어 모두 저장
    if ($method === 'POST' && $type === 'content' && $action === 'save') {
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
            $pdo->exec("DELETE FROM content_slides");

            // 새 데이터 삽입 (한국어 / 영어 / 중국어)
            $stmt = $pdo->prepare("
                INSERT INTO content_slides (
                    slide_order,
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['title_cn'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['description_cn'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? '',
                    $slide['image_url_cn'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Content Slides가 저장되었습니다.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }

    // SaaS Slides 저장 (인증 필요) - 3개 언어 모두 저장
    if ($method === 'POST' && $type === 'saas' && $action === 'save') {
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
            $pdo->exec("DELETE FROM saas_slides");

            // 새 데이터 삽입 (한국어 / 영어 / 중국어)
            $stmt = $pdo->prepare("
                INSERT INTO saas_slides (
                    slide_order,
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($slides as $index => $slide) {
                $stmt->execute([
                    $index + 1,
                    $slide['title_ko'] ?? '',
                    $slide['title_en'] ?? '',
                    $slide['title_cn'] ?? '',
                    $slide['description_ko'] ?? '',
                    $slide['description_en'] ?? '',
                    $slide['description_cn'] ?? '',
                    $slide['image_url_ko'] ?? '',
                    $slide['image_url_en'] ?? '',
                    $slide['image_url_cn'] ?? ''
                ]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'SaaS Slides가 저장되었습니다.']);
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
            SET title_ko = ?, title_en = ?, title_cn = ?,
                description_ko = ?, description_en = ?, description_cn = ?,
                image_url_ko = ?, image_url_en = ?, image_url_cn = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['title_ko'] ?? '',
            $data['title_en'] ?? '',
            $data['title_cn'] ?? '',
            $data['description_ko'] ?? '',
            $data['description_en'] ?? '',
            $data['description_cn'] ?? '',
            $data['image_url_ko'] ?? '',
            $data['image_url_en'] ?? '',
            $data['image_url_cn'] ?? '',
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
            SET title_ko = ?, title_en = ?, title_cn = ?,
                description_ko = ?, description_en = ?, description_cn = ?,
                image_url_ko = ?, image_url_en = ?, image_url_cn = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['title_ko'] ?? '',
            $data['title_en'] ?? '',
            $data['title_cn'] ?? '',
            $data['description_ko'] ?? '',
            $data['description_en'] ?? '',
            $data['description_cn'] ?? '',
            $data['image_url_ko'] ?? '',
            $data['image_url_en'] ?? '',
            $data['image_url_cn'] ?? '',
            $id
        ]);

        echo json_encode(['success' => true, 'message' => '슬라이드가 업데이트되었습니다.']);
        exit;
    }

    // Avatar Slides 가져오기 - 언어별
    if ($method === 'GET' && $type === 'avatar') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                slide_order,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM avatar_slides
            ORDER BY slide_order ASC
        ");
        $stmt->execute();
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    // Content Slides 가져오기 - 언어별
    if ($method === 'GET' && $type === 'content') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                slide_order,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM content_slides
            ORDER BY slide_order ASC
        ");
        $stmt->execute();
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    // SaaS Slides 가져오기 - 언어별
    if ($method === 'GET' && $type === 'saas') {
        $stmt = $pdo->prepare("
            SELECT
                id,
                slide_order,
                title_{$lang} as title,
                description_{$lang} as description,
                image_url_{$lang} as image_url,
                updated_at
            FROM saas_slides
            ORDER BY slide_order ASC
        ");
        $stmt->execute();
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    // Section Images 가져오기 - 언어별 (최신 row만 반환)
    if ($method === 'GET' && $type === 'sections') {
        $stmt = $pdo->prepare("
            SELECT
                s.id,
                s.section_key,
                s.title_{$lang}       AS title,
                s.description_{$lang} AS description,
                s.image_url_{$lang}   AS image_url,
                s.updated_at
            FROM section_images s
            INNER JOIN (
                SELECT section_key, MAX(id) AS max_id
                FROM section_images
                GROUP BY section_key
            ) latest ON s.section_key = latest.section_key
                    AND s.id          = latest.max_id
            ORDER BY s.section_key ASC
        ");
        $stmt->execute();
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    title_ko, title_en, title_cn,
                    description_ko, description_en, description_cn,
                    image_url_ko, image_url_en, image_url_cn
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    title_ko = VALUES(title_ko),
                    title_en = VALUES(title_en),
                    title_cn = VALUES(title_cn),
                    description_ko = VALUES(description_ko),
                    description_en = VALUES(description_en),
                    description_cn = VALUES(description_cn),
                    image_url_ko = VALUES(image_url_ko),
                    image_url_en = VALUES(image_url_en),
                    image_url_cn = VALUES(image_url_cn)
            ");

            foreach ($sections as $section) {
                $stmt->execute([
                    $section['section_key'] ?? '',
                    $section['title_ko'] ?? '',
                    $section['title_en'] ?? '',
                    $section['title_cn'] ?? '',
                    $section['description_ko'] ?? '',
                    $section['description_en'] ?? '',
                    $section['description_cn'] ?? '',
                    $section['image_url_ko'] ?? '',
                    $section['image_url_en'] ?? '',
                    $section['image_url_cn'] ?? ''
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
        // 필수 섹션이 DB에 없으면 자동 초기화
        $required_keys = ['infra_mocap','infra_photo','infra_tech','tech_bigdata_bg','tech_ai_bg','tech_vrpe_bg'];
        $init_stmt = $pdo->prepare("
            INSERT IGNORE INTO section_images (section_key, title_ko, title_en, title_cn,
                description_ko, description_en, description_cn,
                image_url_ko, image_url_en, image_url_cn)
            VALUES (?, '', '', '', '', '', '', '', '', '')
        ");
        foreach ($required_keys as $key) { $init_stmt->execute([$key]); }

        $stmt = $pdo->query("SELECT * FROM section_images ORDER BY section_key ASC");
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['sections' => $sections]);
        exit;
    }

    if ($method === 'GET' && $type === 'avatar_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM avatar_slides ORDER BY slide_order ASC");
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    if ($method === 'GET' && $type === 'content_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM content_slides ORDER BY slide_order ASC");
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }

    if ($method === 'GET' && $type === 'saas_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM saas_slides ORDER BY slide_order ASC");
        $slides = $stmt->fetchAll();
        echo json_encode(['slides' => $slides]);
        exit;
    }


    // ── Step Master Features ────────────────────────────────────
    if ($method === 'GET' && $type === 'stepmaster_features') {
        $stmt = $pdo->prepare("SELECT feat_order, title_{$lang} as title, desc_{$lang} as description FROM stepmaster_features ORDER BY feat_order ASC");
        $stmt->execute();
        echo json_encode(['features' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'GET' && $type === 'stepmaster_features_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM stepmaster_features ORDER BY feat_order ASC");
        echo json_encode(['features' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'POST' && $type === 'stepmaster_features' && $action === 'save') {
        checkAuth();
        $data = json_decode(file_get_contents('php://input'), true);
        $features = $data['features'] ?? [];
        $pdo->exec("DELETE FROM stepmaster_features");
        $stmt = $pdo->prepare("INSERT INTO stepmaster_features (feat_order,title_ko,title_en,title_cn,desc_ko,desc_en,desc_cn) VALUES (?,?,?,?,?,?,?)");
        foreach ($features as $i => $f) {
            $stmt->execute([
                $i + 1,
                $f['title_ko'] ?? '', $f['title_en'] ?? '', $f['title_cn'] ?? '',
                $f['desc_ko']   ?? '', $f['desc_en']   ?? '', $f['desc_cn']   ?? ''
            ]);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    // ── Hero Text ───────────────────────────────────────────────
    if ($method === 'GET' && $type === 'hero_text') {
        $stmt = $pdo->prepare("SELECT badge_{$lang} as badge, title_{$lang} as title, description_{$lang} as description FROM hero_text LIMIT 1");
        $stmt->execute();
        echo json_encode(['text' => $stmt->fetch() ?: new stdClass()]);
        exit;
    }
    if ($method === 'GET' && $type === 'hero_text_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM hero_text LIMIT 1");
        echo json_encode(['text' => $stmt->fetch() ?: new stdClass()]);
        exit;
    }
    if ($method === 'POST' && $type === 'hero_text' && $action === 'save') {
        checkAuth();
        $d = json_decode(file_get_contents('php://input'), true);
        $pdo->beginTransaction();
        try {
            $pdo->exec("DELETE FROM hero_text");
            $stmt = $pdo->prepare("INSERT INTO hero_text (badge_ko,badge_en,badge_cn,title_ko,title_en,title_cn,description_ko,description_en,description_cn) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$d['badge_ko']??'',$d['badge_en']??'',$d['badge_cn']??'',$d['title_ko']??'',$d['title_en']??'',$d['title_cn']??'',$d['description_ko']??'',$d['description_en']??'',$d['description_cn']??'']);
            $pdo->commit();
            echo json_encode(['success'=>true,'message'=>'Hero 텍스트가 저장되었습니다.']);
        } catch(Exception $e) { $pdo->rollBack(); throw $e; }
        exit;
    }

    // ── Hero Stats ──────────────────────────────────────────────
    if ($method === 'GET' && $type === 'hero_stats') {
        $stmt = $pdo->prepare("SELECT stat_order,stat_value,stat_color,label_{$lang} as label FROM hero_stats ORDER BY stat_order ASC");
        $stmt->execute();
        echo json_encode(['stats' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'GET' && $type === 'hero_stats_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM hero_stats ORDER BY stat_order ASC");
        echo json_encode(['stats' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'POST' && $type === 'hero_stats' && $action === 'save') {
        checkAuth();
        $d = json_decode(file_get_contents('php://input'), true);
        $stats = $d['stats'] ?? [];
        $pdo->beginTransaction();
        try {
            $pdo->exec("DELETE FROM hero_stats");
            $stmt = $pdo->prepare("INSERT INTO hero_stats (stat_order,stat_value,stat_color,label_ko,label_en,label_cn) VALUES (?,?,?,?,?,?)");
            foreach ($stats as $i => $s) {
                $stmt->execute([$i+1,$s['stat_value']??'',$s['stat_color']??'slate',$s['label_ko']??'',$s['label_en']??'',$s['label_cn']??'']);
            }
            $pdo->commit();
            echo json_encode(['success'=>true,'message'=>'Hero 통계가 저장되었습니다.']);
        } catch(Exception $e) { $pdo->rollBack(); throw $e; }
        exit;
    }

    // ── Problem Slides ──────────────────────────────────────────
    if ($method === 'GET' && $type === 'problem') {
        $stmt = $pdo->prepare("SELECT id,slide_order,title_{$lang} as title,description_{$lang} as description,image_url_{$lang} as image_url,updated_at FROM problem_slides ORDER BY slide_order ASC");
        $stmt->execute();
        echo json_encode(['slides' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'GET' && $type === 'problem_admin') {
        checkAuth();
        $stmt = $pdo->query("SELECT * FROM problem_slides ORDER BY slide_order ASC");
        echo json_encode(['slides' => $stmt->fetchAll()]);
        exit;
    }
    if ($method === 'POST' && $type === 'problem' && $action === 'save') {
        checkAuth();
        $d = json_decode(file_get_contents('php://input'), true);
        $slides = $d['slides'] ?? [];
        if (empty($slides)) { http_response_code(400); echo json_encode(['error'=>'슬라이드 데이터가 없습니다.']); exit; }
        $pdo->beginTransaction();
        try {
            $pdo->exec("DELETE FROM problem_slides");
            $stmt = $pdo->prepare("INSERT INTO problem_slides (slide_order,title_ko,title_en,title_cn,description_ko,description_en,description_cn,image_url_ko,image_url_en,image_url_cn) VALUES (?,?,?,?,?,?,?,?,?,?)");
            foreach ($slides as $i => $s) {
                $stmt->execute([$i+1,$s['title_ko']??'',$s['title_en']??'',$s['title_cn']??'',$s['description_ko']??'',$s['description_en']??'',$s['description_cn']??'',$s['image_url_ko']??'',$s['image_url_en']??'',$s['image_url_cn']??'']);
            }
            $pdo->commit();
            echo json_encode(['success'=>true,'message'=>'Problem Slides가 저장되었습니다.']);
        } catch(Exception $e) { $pdo->rollBack(); throw $e; }
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);


} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
