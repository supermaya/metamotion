-- 모든 테이블 구조 확인
-- phpMyAdmin에서 하나씩 실행하세요

-- 1. hero_slides 테이블 구조
DESCRIBE hero_slides;

-- 2. solution_slides 테이블 구조
DESCRIBE solution_slides;

-- 3. section_images 테이블 구조
DESCRIBE section_images;

-- 4. 각 테이블의 컬럼 목록 확인
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'hero_slides'
AND TABLE_SCHEMA = 'playelga_metamotion'
ORDER BY ORDINAL_POSITION;

SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'solution_slides'
AND TABLE_SCHEMA = 'playelga_metamotion'
ORDER BY ORDINAL_POSITION;

SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'section_images'
AND TABLE_SCHEMA = 'playelga_metamotion'
ORDER BY ORDINAL_POSITION;
