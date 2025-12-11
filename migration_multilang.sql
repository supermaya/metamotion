-- ================================================================
-- 다국어 이미지 지원을 위한 데이터베이스 마이그레이션
-- 실행 전 반드시 데이터베이스 백업을 권장합니다
-- ================================================================

-- 1. hero_slides 테이블 업데이트
-- 기존 컬럼을 한국어 버전으로 변경하고 영문 컬럼 추가
ALTER TABLE hero_slides
    ADD COLUMN title_ko VARCHAR(255) AFTER slide_order,
    ADD COLUMN title_en VARCHAR(255) AFTER title_ko,
    ADD COLUMN description_ko TEXT AFTER title_en,
    ADD COLUMN description_en TEXT AFTER description_ko,
    ADD COLUMN image_url_ko TEXT AFTER description_en,
    ADD COLUMN image_url_en TEXT AFTER image_url_ko;

-- 기존 데이터를 한국어 컬럼으로 복사
UPDATE hero_slides
SET
    title_ko = title,
    description_ko = description,
    image_url_ko = image_url
WHERE title_ko IS NULL;

-- 영문 데이터 초기값 설정 (placeholder)
UPDATE hero_slides
SET
    title_en = CONCAT(title, ' (English version)'),
    description_en = CONCAT(description, ' (English version)'),
    image_url_en = image_url
WHERE title_en IS NULL;

-- 기존 컬럼 삭제
ALTER TABLE hero_slides
    DROP COLUMN title,
    DROP COLUMN description,
    DROP COLUMN image_url;

-- ================================================================

-- 2. solution_slides 테이블 업데이트
-- 기존 컬럼을 한국어 버전으로 변경하고 영문 컬럼 추가
ALTER TABLE solution_slides
    ADD COLUMN title_ko VARCHAR(255) AFTER slide_order,
    ADD COLUMN title_en VARCHAR(255) AFTER title_ko,
    ADD COLUMN description_ko TEXT AFTER title_en,
    ADD COLUMN description_en TEXT AFTER description_ko,
    ADD COLUMN image_url_ko TEXT AFTER description_en,
    ADD COLUMN image_url_en TEXT AFTER image_url_ko;

-- 기존 데이터를 한국어 컬럼으로 복사
UPDATE solution_slides
SET
    title_ko = title,
    description_ko = description,
    image_url_ko = image_url
WHERE title_ko IS NULL;

-- 영문 데이터 초기값 설정 (placeholder)
UPDATE solution_slides
SET
    title_en = CONCAT(title, ' (English version)'),
    description_en = CONCAT(description, ' (English version)'),
    image_url_en = image_url
WHERE title_en IS NULL;

-- 기존 컬럼 삭제
ALTER TABLE solution_slides
    DROP COLUMN title,
    DROP COLUMN description,
    DROP COLUMN image_url;

-- ================================================================

-- 3. section_images 테이블 업데이트
-- 기존 컬럼을 한국어 버전으로 변경하고 영문 컬럼 추가
ALTER TABLE section_images
    ADD COLUMN title_ko VARCHAR(255) AFTER section_key,
    ADD COLUMN title_en VARCHAR(255) AFTER title_ko,
    ADD COLUMN description_ko TEXT AFTER title_en,
    ADD COLUMN description_en TEXT AFTER description_ko,
    ADD COLUMN image_url_ko TEXT AFTER description_en,
    ADD COLUMN image_url_en TEXT AFTER image_url_ko;

-- 기존 데이터를 한국어 컬럼으로 복사
UPDATE section_images
SET
    title_ko = title,
    description_ko = description,
    image_url_ko = image_url
WHERE title_ko IS NULL;

-- 영문 데이터 초기값 설정 (placeholder)
UPDATE section_images
SET
    title_en = CONCAT(title, ' (English version)'),
    description_en = CONCAT(description, ' (English version)'),
    image_url_en = image_url
WHERE title_en IS NULL;

-- 기존 컬럼 삭제
ALTER TABLE section_images
    DROP COLUMN title,
    DROP COLUMN description,
    DROP COLUMN image_url;

-- ================================================================
-- 마이그레이션 완료
-- 이제 각 테이블은 _ko와 _en 접미사를 가진 컬럼으로 구성됩니다
-- ================================================================
