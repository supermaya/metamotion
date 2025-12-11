<?php
// API 및 데이터베이스 연결 테스트 파일
// 사용 후 반드시 삭제하세요!

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>메타모션 API 테스트</h1>";
echo "<hr>";

// 1. config.php 로드 테스트
echo "<h2>1. Config 파일 로드</h2>";
try {
    require_once 'config.php';
    echo "✅ config.php 로드 성공<br>";
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
    echo "DB_USER: " . DB_USER . "<br>";
} catch (Exception $e) {
    echo "❌ config.php 로드 실패: " . $e->getMessage() . "<br>";
    exit;
}

echo "<hr>";

// 2. 데이터베이스 연결 테스트
echo "<h2>2. 데이터베이스 연결</h2>";
try {
    $pdo = getDBConnection();
    echo "✅ 데이터베이스 연결 성공<br>";
} catch (Exception $e) {
    echo "❌ 데이터베이스 연결 실패: " . $e->getMessage() . "<br>";
    exit;
}

echo "<hr>";

// 3. 테이블 존재 확인
echo "<h2>3. 테이블 존재 확인</h2>";
try {
    $tables = ['admin_users', 'hero_slides', 'solution_slides'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ 테이블 '$table' 존재<br>";
        } else {
            echo "❌ 테이블 '$table' 없음<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ 테이블 확인 실패: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// 4. Hero Slides 데이터 조회
echo "<h2>4. Hero Slides 데이터</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM hero_slides ORDER BY slide_order ASC");
    $heroSlides = $stmt->fetchAll();

    if (count($heroSlides) > 0) {
        echo "✅ Hero Slides 데이터 " . count($heroSlides) . "개 발견<br><br>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>순서</th><th>제목</th><th>설명</th><th>이미지 URL</th></tr>";
        foreach ($heroSlides as $slide) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($slide['id']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['slide_order']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['title']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['description']) . "</td>";
            echo "<td style='max-width: 300px; word-wrap: break-word;'>" . htmlspecialchars($slide['image_url']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Hero Slides 데이터가 비어있습니다<br>";
    }
} catch (Exception $e) {
    echo "❌ Hero Slides 조회 실패: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// 5. Solution Slides 데이터 조회
echo "<h2>5. Solution Slides 데이터</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM solution_slides ORDER BY slide_order ASC");
    $solutionSlides = $stmt->fetchAll();

    if (count($solutionSlides) > 0) {
        echo "✅ Solution Slides 데이터 " . count($solutionSlides) . "개 발견<br><br>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>순서</th><th>제목</th><th>설명</th><th>이미지 URL</th></tr>";
        foreach ($solutionSlides as $slide) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($slide['id']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['slide_order']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['title']) . "</td>";
            echo "<td>" . htmlspecialchars($slide['description']) . "</td>";
            echo "<td style='max-width: 300px; word-wrap: break-word;'>" . htmlspecialchars($slide['image_url']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Solution Slides 데이터가 비어있습니다<br>";
    }
} catch (Exception $e) {
    echo "❌ Solution Slides 조회 실패: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// 6. API 엔드포인트 테스트
echo "<h2>6. API 엔드포인트 테스트</h2>";
echo "<a href='api.php?type=hero' target='_blank'>Hero Slides API 테스트 (api.php?type=hero)</a><br>";
echo "<a href='api.php?type=solution' target='_blank'>Solution Slides API 테스트 (api.php?type=solution)</a><br>";

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ 테스트 완료 후 이 파일(test_api.php)을 반드시 삭제하세요!</p>";
?>
