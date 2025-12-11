-- 영문 페이지 이미지 문제 디버깅 쿼리

-- 1. hero_slides의 영문 이미지 URL 확인
SELECT
    id,
    slide_order,
    title_ko,
    title_en,
    image_url_ko,
    image_url_en
FROM hero_slides
ORDER BY slide_order;

-- 2. solution_slides의 영문 이미지 URL 확인
SELECT
    id,
    slide_order,
    title_ko,
    title_en,
    image_url_ko,
    image_url_en
FROM solution_slides
ORDER BY slide_order;

-- 3. section_images의 영문 이미지 URL 확인
SELECT
    section_key,
    title_ko,
    title_en,
    image_url_ko,
    image_url_en
FROM section_images;

-- 4. 비어있는 영문 이미지 URL 찾기
SELECT 'hero_slides' as table_name, id, title_en
FROM hero_slides
WHERE image_url_en IS NULL OR image_url_en = ''
UNION ALL
SELECT 'solution_slides' as table_name, id, title_en
FROM solution_slides
WHERE image_url_en IS NULL OR image_url_en = ''
UNION ALL
SELECT 'section_images' as table_name, id, title_en
FROM section_images
WHERE image_url_en IS NULL OR image_url_en = '';
