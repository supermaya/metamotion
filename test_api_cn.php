<?php
// 중국어 API 응답 테스트 스크립트
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>중국어 API 테스트</h1>";

try {
    $pdo = getDBConnection();

    echo "<h2>1. Hero Slides 테이블 확인 (모든 언어 필드)</h2>";
    $stmt = $pdo->query("SELECT id, slide_order, title_ko, title_en, title_cn, description_ko, description_en, description_cn FROM hero_slides ORDER BY slide_order ASC LIMIT 3");
    $heroSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($heroSlides);
    echo "</pre>";

    echo "<h2>2. Solution Slides 테이블 확인 (모든 언어 필드)</h2>";
    $stmt = $pdo->query("SELECT id, slide_order, title_ko, title_en, title_cn, description_ko, description_en, description_cn FROM solution_slides ORDER BY slide_order ASC");
    $solutionSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($solutionSlides);
    echo "</pre>";

    echo "<h2>3. Section Images 테이블 확인 (모든 언어 필드)</h2>";
    $stmt = $pdo->query("SELECT section_key, title_ko, title_en, title_cn, description_ko, description_en, description_cn FROM section_images");
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($sections);
    echo "</pre>";

    echo "<h2>4. API 응답 시뮬레이션 (lang=cn)</h2>";
    $lang = 'cn';
    $stmt = $pdo->prepare("
        SELECT
            id,
            slide_order,
            title_{$lang} as title,
            description_{$lang} as description,
            image_url_{$lang} as image_url
        FROM hero_slides
        ORDER BY slide_order ASC
        LIMIT 3
    ");
    $stmt->execute();
    $apiResponse = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($apiResponse);
    echo "</pre>";

    echo "<h2>5. 중국어 필드 컬럼 존재 확인</h2>";
    $stmt = $pdo->query("DESCRIBE hero_slides");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

} catch (Exception $e) {
    echo "<p style='color: red;'>오류: " . $e->getMessage() . "</p>";
}
?>
