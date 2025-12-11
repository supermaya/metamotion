-- 현재 데이터베이스의 데이터 확인 쿼리
-- phpMyAdmin에서 하나씩 실행하여 현재 상태를 확인하세요

-- 1. hero_slides 데이터 확인
SELECT * FROM hero_slides;

-- 2. solution_slides 데이터 확인
SELECT * FROM solution_slides;

-- 3. section_images 데이터 확인
SELECT * FROM section_images;

-- 4. hero_slides 개수 확인
SELECT COUNT(*) as hero_count FROM hero_slides;

-- 5. solution_slides 개수 확인
SELECT COUNT(*) as solution_count FROM solution_slides;

-- 6. section_images 개수 확인
SELECT COUNT(*) as section_count FROM section_images;
