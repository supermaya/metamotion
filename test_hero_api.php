<?php
header('Content-Type: text/html; charset=UTF-8');
$base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
$tests = [
    'hero_text ko'   => "$base/api.php?type=hero_text&lang=ko",
    'hero_text en'   => "$base/api.php?type=hero_text&lang=en",
    'hero_text cn'   => "$base/api.php?type=hero_text&lang=cn",
    'hero_stats ko'  => "$base/api.php?type=hero_stats&lang=ko",
    'hero_stats en'  => "$base/api.php?type=hero_stats&lang=en",
    'hero_stats cn'  => "$base/api.php?type=hero_stats&lang=cn",
];
?><!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>API 직접 테스트</title>
<style>
body{font-family:monospace;background:#0f172a;color:#e2e8f0;padding:24px}
h2{color:#38bdf8}h3{color:#a78bfa;margin-top:20px}
pre{background:#1e293b;padding:12px;border-radius:8px;white-space:pre-wrap;word-break:break-all;font-size:12px}
.ok{color:#4ade80}.err{color:#f87171}
</style></head>
<body>
<h2>🔍 api.php 실제 응답 테스트</h2>
<?php foreach ($tests as $label => $url): 
    $ctx = stream_context_create(['http'=>['timeout'=>10,'ignore_errors'=>true]]);
    $raw = @file_get_contents($url, false, $ctx);
    $http_code = isset($http_response_header[0]) ? $http_response_header[0] : 'N/A';
    $decoded = json_decode($raw, true);
    $json_ok  = json_last_error() === JSON_ERROR_NONE;
?>
<h3><?= $label ?></h3>
<p><?= $http_code ?> — JSON 파싱: <span class="<?= $json_ok ? 'ok' : 'err' ?>"><?= $json_ok ? '✅ 성공' : '❌ 실패 ('.json_last_error_msg().')' ?></span></p>
<pre><?= htmlspecialchars(substr($raw, 0, 1000)) ?></pre>
<?php endforeach; ?>
<p style="color:#64748b;margin-top:32px">⚠️ 확인 후 이 파일(test_hero_api.php)을 삭제하세요.</p>
</body></html>
