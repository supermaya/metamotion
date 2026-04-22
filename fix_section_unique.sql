-- ============================================================
-- section_images 테이블 section_key 중복 제거 및 UNIQUE 제약 추가
-- 서버 MySQL에서 실행하세요
-- ============================================================

-- 1) 중복 row 제거 (section_key별로 id가 가장 큰 최신 row만 남김)
DELETE t1
FROM section_images t1
INNER JOIN section_images t2
  ON t1.section_key = t2.section_key
 AND t1.id < t2.id;

-- 2) section_key에 UNIQUE 제약 추가
--    이미 존재하면 에러가 나므로, IF NOT EXISTS 처리
SET @cnt = (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'section_images'
      AND CONSTRAINT_NAME = 'uq_section_key'
);
SET @sql = IF(@cnt = 0,
    'ALTER TABLE section_images ADD UNIQUE KEY uq_section_key (section_key)',
    'SELECT "UNIQUE KEY already exists" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3) 6개 필수 섹션 데이터 없으면 삽입 (section_key 겹치면 무시)
INSERT IGNORE INTO section_images
    (section_key, title_ko, title_en, title_cn,
     description_ko, description_en, description_cn,
     image_url_ko, image_url_en, image_url_cn)
VALUES
    ('infra_mocap',    '','','','','','','','',''),
    ('infra_photo',    '','','','','','','','',''),
    ('infra_tech',     '','','','','','','','',''),
    ('tech_bigdata_bg','','','','','','','','',''),
    ('tech_ai_bg',     '','','','','','','','',''),
    ('tech_vrpe_bg',   '','','','','','','','','');

SELECT CONCAT('section_images rows: ', COUNT(*)) AS status FROM section_images;
