<?php
// Direct database test for Chinese data
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Direct Database Test - Chinese Fields</h1>";

try {
    $pdo = getDBConnection();

    // Test 1: Check if Chinese columns exist
    echo "<h2>1. Table Structure (hero_slides)</h2>";
    $stmt = $pdo->query("SHOW COLUMNS FROM hero_slides LIKE '%_cn'");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

    // Test 2: Get all Chinese data
    echo "<h2>2. All Chinese Data (title_cn, description_cn, image_url_cn)</h2>";
    $stmt = $pdo->query("SELECT id, slide_order, title_cn, description_cn, image_url_cn FROM hero_slides ORDER BY slide_order ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    // Test 3: Simulate API query with lang=cn
    echo "<h2>3. Simulated API Query (lang=cn)</h2>";
    $lang = 'cn';
    $query = "
        SELECT
            id,
            slide_order,
            title_{$lang} as title,
            description_{$lang} as description,
            image_url_{$lang} as image_url
        FROM hero_slides
        ORDER BY slide_order ASC
        LIMIT 3
    ";
    echo "<p>Query: <code>" . htmlspecialchars($query) . "</code></p>";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Result:</h3>";
    echo "<pre>";
    print_r($slides);
    echo "</pre>";

    // Test 4: JSON output like API
    echo "<h2>4. JSON Output (like API response)</h2>";
    echo "<pre>";
    echo json_encode(['slides' => $slides], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "</pre>";

    // Test 5: Check for NULL or empty values
    echo "<h2>5. Data Analysis</h2>";
    foreach ($slides as $index => $slide) {
        echo "<p><strong>Slide " . ($index + 1) . ":</strong></p>";
        echo "<ul>";
        echo "<li>Title (Chinese): " . ($slide['title'] ?? 'NULL/EMPTY') . "</li>";
        echo "<li>Description (Chinese): " . ($slide['description'] ?? 'NULL/EMPTY') . "</li>";
        echo "<li>Image URL (Chinese): " . ($slide['image_url'] ?? 'NULL/EMPTY') . "</li>";
        echo "</ul>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
