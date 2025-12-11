-- 영문 페이지 이미지가 안 보이는 문제 진단

-- 1. hero_slides 데이터 비교
SELECT
    id,
    slide_order,
    title_ko,
    title_en,
    CASE
        WHEN image_url_ko IS NULL OR image_url_ko = '' THEN 'EMPTY'
        ELSE CONCAT('OK (', LENGTH(image_url_ko), ' chars)')
    END as image_url_ko_status,
    CASE
        WHEN image_url_en IS NULL OR image_url_en = '' THEN 'EMPTY'
        ELSE CONCAT('OK (', LENGTH(image_url_en), ' chars)')
    END as image_url_en_status,
    LEFT(image_url_ko, 60) as url_ko_preview,
    LEFT(image_url_en, 60) as url_en_preview
FROM hero_slides
ORDER BY slide_order;

-- 2. solution_slides 데이터 비교
SELECT
    id,
    slide_order,
    title_ko,
    title_en,
    CASE
        WHEN image_url_ko IS NULL OR image_url_ko = '' THEN 'EMPTY'
        ELSE 'OK'
    END as image_url_ko_status,
    CASE
        WHEN image_url_en IS NULL OR image_url_en = '' THEN 'EMPTY'
        ELSE 'OK'
    END as image_url_en_status
FROM solution_slides
ORDER BY slide_order;

-- 3. 영문 이미지가 비어있는 항목 찾기
SELECT 'hero_slides' as table_name, id, title_ko, title_en
FROM hero_slides
WHERE image_url_en IS NULL OR image_url_en = ''
UNION ALL
SELECT 'solution_slides', id, title_ko, title_en
FROM solution_slides
WHERE image_url_en IS NULL OR image_url_en = '';

-- 4. 한글/영문 이미지 URL이 동일한지 확인
SELECT
    'hero_slides' as table_name,
    id,
    title_ko,
    CASE
        WHEN image_url_ko = image_url_en THEN 'SAME'
        ELSE 'DIFFERENT'
    END as url_comparison
FROM hero_slides
UNION ALL
SELECT
    'solution_slides',
    id,
    title_ko,
    CASE
        WHEN image_url_ko = image_url_en THEN 'SAME'
        ELSE 'DIFFERENT'
    END
FROM solution_slides;
