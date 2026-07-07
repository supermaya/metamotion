-- hero_stats 중복 제거: stat_order 1~4만 남기고 나머지 삭제
-- 현재 8행 → 4행으로 정리

-- 각 stat_order에서 가장 최근(id 높은) 행만 남기고 나머지 삭제
DELETE FROM hero_stats
WHERE id NOT IN (
    SELECT max_id FROM (
        SELECT MAX(id) AS max_id
        FROM hero_stats
        GROUP BY stat_order
    ) AS t
);

-- stat_order 5 이상 삭제 (4개 카드만 유지)
DELETE FROM hero_stats WHERE stat_order > 4;

-- stat_order 재정렬 (1,2,3,4 보장)
SET @r = 0;
UPDATE hero_stats SET stat_order = (@r := @r + 1) ORDER BY stat_order ASC;

-- 결과 확인
SELECT id, stat_order, stat_value, stat_color, label_ko, label_en, label_cn FROM hero_stats ORDER BY stat_order;
