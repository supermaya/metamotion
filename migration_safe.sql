-- ================================================================
-- 안전한 다국어 이미지 지원 마이그레이션 (중복 실행 방지)
-- 이미 컬럼이 있으면 건너뛰고, 없으면 추가합니다
-- ================================================================

-- 1. hero_slides 테이블 업데이트
-- 컬럼이 없을 때만 추가 (중복 실행 방지)

-- title_ko 컬럼 추가 (없을 경우)
SET @dbname = 'playelga_metamotion';
SET @tablename = 'hero_slides';
SET @columnname = 'title_ko';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN title_ko VARCHAR(255) AFTER slide_order'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- title_en 컬럼 추가
SET @columnname = 'title_en';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN title_en VARCHAR(255) AFTER title_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_ko 컬럼 추가
SET @columnname = 'description_ko';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN description_ko TEXT AFTER title_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_en 컬럼 추가
SET @columnname = 'description_en';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN description_en TEXT AFTER description_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_ko 컬럼 추가
SET @columnname = 'image_url_ko';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN image_url_ko TEXT AFTER description_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_en 컬럼 추가
SET @columnname = 'image_url_en';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  'ALTER TABLE hero_slides ADD COLUMN image_url_en TEXT AFTER image_url_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 기존 title, description, image_url 컬럼이 있으면 데이터 복사 후 삭제
SET @columnname = 'title';
SET @hasOldColumn = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_NAME = @tablename)
      AND (TABLE_SCHEMA = @dbname)
      AND (COLUMN_NAME = @columnname)
  ) > 0, 1, 0
));

-- 기존 데이터를 한국어 컬럼으로 복사 (기존 컬럼이 있을 경우만)
SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE hero_slides SET title_ko = title, description_ko = description, image_url_ko = image_url WHERE title_ko IS NULL OR title_ko = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

-- 영문 데이터 초기값 설정
SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE hero_slides SET title_en = CONCAT(title, \' (English version)\'), description_en = CONCAT(description, \' (English version)\'), image_url_en = image_url WHERE title_en IS NULL OR title_en = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

-- 기존 컬럼 삭제
SET @preparedStatement = IF(@hasOldColumn = 1,
  'ALTER TABLE hero_slides DROP COLUMN title, DROP COLUMN description, DROP COLUMN image_url',
  'SELECT 1'
);
PREPARE dropIfExists FROM @preparedStatement;
EXECUTE dropIfExists;
DEALLOCATE PREPARE dropIfExists;

-- ================================================================
-- solution_slides도 동일한 방식으로 처리
-- ================================================================

SET @tablename = 'solution_slides';

-- title_ko
SET @columnname = 'title_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN title_ko VARCHAR(255) AFTER slide_order'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- title_en
SET @columnname = 'title_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN title_en VARCHAR(255) AFTER title_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_ko
SET @columnname = 'description_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN description_ko TEXT AFTER title_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_en
SET @columnname = 'description_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN description_en TEXT AFTER description_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_ko
SET @columnname = 'image_url_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN image_url_ko TEXT AFTER description_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_en
SET @columnname = 'image_url_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE solution_slides ADD COLUMN image_url_en TEXT AFTER image_url_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 기존 데이터 복사 및 컬럼 삭제
SET @columnname = 'title';
SET @hasOldColumn = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0, 1, 0
));

SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE solution_slides SET title_ko = title, description_ko = description, image_url_ko = image_url WHERE title_ko IS NULL OR title_ko = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE solution_slides SET title_en = CONCAT(title, \' (English version)\'), description_en = CONCAT(description, \' (English version)\'), image_url_en = image_url WHERE title_en IS NULL OR title_en = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

SET @preparedStatement = IF(@hasOldColumn = 1,
  'ALTER TABLE solution_slides DROP COLUMN title, DROP COLUMN description, DROP COLUMN image_url',
  'SELECT 1'
);
PREPARE dropIfExists FROM @preparedStatement;
EXECUTE dropIfExists;
DEALLOCATE PREPARE dropIfExists;

-- ================================================================
-- section_images도 동일한 방식으로 처리
-- ================================================================

SET @tablename = 'section_images';

-- title_ko
SET @columnname = 'title_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN title_ko VARCHAR(255) AFTER section_key'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- title_en
SET @columnname = 'title_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN title_en VARCHAR(255) AFTER title_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_ko
SET @columnname = 'description_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN description_ko TEXT AFTER title_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- description_en
SET @columnname = 'description_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN description_en TEXT AFTER description_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_ko
SET @columnname = 'image_url_ko';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN image_url_ko TEXT AFTER description_en'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- image_url_en
SET @columnname = 'image_url_en';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE section_images ADD COLUMN image_url_en TEXT AFTER image_url_ko'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 기존 데이터 복사 및 컬럼 삭제
SET @columnname = 'title';
SET @hasOldColumn = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = @tablename AND TABLE_SCHEMA = @dbname AND COLUMN_NAME = @columnname) > 0, 1, 0
));

SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE section_images SET title_ko = title, description_ko = description, image_url_ko = image_url WHERE title_ko IS NULL OR title_ko = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

SET @preparedStatement = IF(@hasOldColumn = 1,
  'UPDATE section_images SET title_en = CONCAT(title, \' (English version)\'), description_en = CONCAT(description, \' (English version)\'), image_url_en = image_url WHERE title_en IS NULL OR title_en = \'\'',
  'SELECT 1'
);
PREPARE updateIfExists FROM @preparedStatement;
EXECUTE updateIfExists;
DEALLOCATE PREPARE updateIfExists;

SET @preparedStatement = IF(@hasOldColumn = 1,
  'ALTER TABLE section_images DROP COLUMN title, DROP COLUMN description, DROP COLUMN image_url',
  'SELECT 1'
);
PREPARE dropIfExists FROM @preparedStatement;
EXECUTE dropIfExists;
DEALLOCATE PREPARE dropIfExists;

-- ================================================================
-- 마이그레이션 완료
-- ================================================================
SELECT '마이그레이션이 안전하게 완료되었습니다!' as result;
