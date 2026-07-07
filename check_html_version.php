<?php
// 서버에 올라간 index_en.html이 hero_text 로딩 코드를 포함하는지 확인
$file = __DIR__ . '/index_en.html';
$content = file_get_contents($file);

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>파일 버전 확인</title>
<style>body{font-family:monospace;background:#0f172a;color:#e2e8f0;padding:24px} .ok{color:#4ade80} .err{color:#f87171}</style>
</head>
<body>
<h2>서버 HTML 파일 버전 확인</h2>
<table border="1" style="border-collapse:collapse;width:100%">
<tr><th style="padding:8px;background:#1e3a5f">확인 항목</th><th style="padding:8px;background:#1e3a5f">결과</th></tr>
<?php
$checks = [
    'hero-badge ID 존재'         => 'id="hero-badge"',
    'hero-title-line1 ID 존재'   => 'id="hero-title-line1"',
    'hero-title-line2 ID 존재'   => 'id="hero-title-line2"',
    'hero-description ID 존재'   => 'id="hero-description"',
    'stat-val-0 ID 존재'         => 'id="stat-val-0"',
    'hero_text API 호출 존재'     => 'hero_text&lang=en',
    'hero_stats API 호출 존재'    => 'hero_stats&lang=en',
];
foreach ($checks as $label => $needle):
    $found = strpos($content, $needle) !== false;
?>
<tr>
  <td style="padding:8px"><?= $label ?></td>
  <td style="padding:8px" class="<?= $found ? 'ok' : 'err' ?>"><?= $found ? '✅ 있음' : '❌ 없음 → 구버전 파일' ?></td>
</tr>
<?php endforeach; ?>
</table>

<h2 style="margin-top:24px">index_cn.html 확인</h2>
<?php
$file_cn = __DIR__ . '/index_cn.html';
$content_cn = file_get_contents($file_cn);
$checks_cn = [
    'hero-badge ID 존재'       => 'id="hero-badge"',
    'hero_text CN API 호출'    => 'hero_text&lang=cn',
    'hero_stats CN API 호출'   => 'hero_stats&lang=cn',
    '중문 기본값(动作技术) 존재' => '动作技术',
];
echo '<table border="1" style="border-collapse:collapse;width:100%"><tr><th style="padding:8px;background:#1e3a5f">확인 항목</th><th style="padding:8px;background:#1e3a5f">결과</th></tr>';
foreach ($checks_cn as $label => $needle):
    $found = strpos($content_cn, $needle) !== false;
    echo "<tr><td style='padding:8px'>$label</td><td style='padding:8px' class='".($found?'ok':'err')."'>".($found?'✅ 있음':'❌ 없음 → 구버전 파일')."</td></tr>";
endforeach;
echo '</table>';
?>

<p style="color:#64748b;margin-top:24px">확인 후 check_html_version.php 삭제하세요.</p>
</body>
</html>
