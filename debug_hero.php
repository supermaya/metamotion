<?php
require_once 'config.php';
header('Content-Type: text/html; charset=UTF-8');

function q($pdo, $sql) {
    try {
        $r = $pdo->query($sql);
        return $r ? $r->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Exception $e) {
        return ['ERROR' => $e->getMessage()];
    }
}

try {
    $pdo = getDBConnection();
    $ok = true;
} catch (Exception $e) {
    $ok = false;
    $dbErr = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>Hero 진단</title>
<style>
body{font-family:monospace;background:#0f172a;color:#e2e8f0;padding:24px;margin:0}
h2{color:#38bdf8;border-bottom:1px solid #334155;padding-bottom:8px}
h3{color:#a78bfa;margin-top:24px}
table{border-collapse:collapse;width:100%;margin-bottom:24px}
th{background:#1e3a5f;color:#7dd3fc;padding:8px 12px;text-align:left;border:1px solid #334155}
td{padding:7px 12px;border:1px solid #334155;vertical-align:top;word-break:break-all}
.ok{color:#4ade80}.err{color:#f87171}.warn{color:#fb923c}
pre{background:#1e293b;padding:12px;border-radius:8px;white-space:pre-wrap;overflow-x:auto}
.box{background:#1e293b;border:1px solid #334155;border-radius:8px;padding:16px;margin-bottom:16px}
</style>
</head>
<body>
<h2>🔍 METAMOTION Hero 진단 페이지</h2>

<?php if (!$ok): ?>
<p class="err">❌ DB 연결 실패: <?= htmlspecialchars($dbErr) ?></p>
<?php else: ?>
<p class="ok">✅ DB 연결 성공</p>

<?php
// ── 1. 테이블 존재 확인 ──────────────────────────────────
$tables = ['hero_text', 'hero_stats', 'problem_slides'];
?>
<h2>1. 테이블 존재 여부</h2>
<table>
<tr><th>테이블</th><th>존재</th><th>행 수</th></tr>
<?php foreach ($tables as $t): 
    $exists = $pdo->query("SHOW TABLES LIKE '$t'")->rowCount() > 0;
    $count  = $exists ? $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn() : '-';
?>
<tr>
  <td><?= $t ?></td>
  <td class="<?= $exists ? 'ok' : 'err' ?>"><?= $exists ? '✅ 있음' : '❌ 없음 → create_hero_tables.sql 실행 필요' ?></td>
  <td><?= $count ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php
// ── 2. hero_text 데이터 ──────────────────────────────────
?>
<h2>2. hero_text 데이터</h2>
<?php
try {
    $rows = $pdo->query("SELECT * FROM hero_text LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo '<p class="warn">⚠️ 데이터 없음 — admin에서 한 번 저장 필요</p>';
    } else {
        echo '<table><tr>';
        foreach (array_keys($rows[0]) as $col) echo "<th>$col</th>";
        echo '</tr>';
        foreach ($rows as $row) {
            echo '<tr>';
            foreach ($row as $v) echo '<td>' . nl2br(htmlspecialchars($v)) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (Exception $e) {
    echo '<p class="err">❌ ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

<?php
// ── 3. hero_stats 데이터 ────────────────────────────────
?>
<h2>3. hero_stats 데이터</h2>
<?php
try {
    $rows = $pdo->query("SELECT * FROM hero_stats ORDER BY stat_order")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo '<p class="warn">⚠️ 데이터 없음 — admin에서 한 번 저장 필요</p>';
    } else {
        echo '<table><tr>';
        foreach (array_keys($rows[0]) as $col) echo "<th>$col</th>";
        echo '</tr>';
        foreach ($rows as $row) {
            echo '<tr>';
            foreach ($row as $v) echo '<td>' . htmlspecialchars($v) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (Exception $e) {
    echo '<p class="err">❌ ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

<?php
// ── 4. API 엔드포인트 시뮬레이션 ───────────────────────
?>
<h2>4. API 응답 시뮬레이션</h2>
<?php
$langs = ['ko', 'en', 'cn'];
foreach ($langs as $lang):
?>
<h3>hero_stats (lang=<?= $lang ?>)</h3>
<div class="box">
<?php
try {
    $stmt = $pdo->prepare("SELECT stat_order, stat_value, stat_color, label_{$lang} as label FROM hero_stats ORDER BY stat_order ASC");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>' . json_encode(['stats' => $data], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</pre>';
} catch (Exception $e) {
    echo '<p class="err">❌ ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
</div>
<h3>hero_text (lang=<?= $lang ?>)</h3>
<div class="box">
<?php
try {
    $stmt = $pdo->prepare("SELECT badge_{$lang} as badge, title_{$lang} as title, description_{$lang} as description FROM hero_text LIMIT 1");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<pre>' . json_encode(['text' => $data ?: new stdClass()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</pre>';
} catch (Exception $e) {
    echo '<p class="err">❌ ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
</div>
<?php endforeach; ?>

<?php
// ── 5. 실제 API URL 링크 ────────────────────────────────
?>
<h2>5. 실제 API URL 직접 확인</h2>
<div class="box">
<?php
$base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api.php';
$urls = [
    "$base?type=hero_text&lang=ko",
    "$base?type=hero_text&lang=en",
    "$base?type=hero_stats&lang=ko",
    "$base?type=hero_stats&lang=en",
];
foreach ($urls as $u) echo "<p><a href='$u' target='_blank' style='color:#38bdf8'>$u</a></p>";
?>
</div>

<?php endif; ?>
<p style="color:#64748b;margin-top:32px">⚠️ 진단 완료 후 이 파일(debug_hero.php)을 서버에서 삭제하세요.</p>
</body>
</html>
