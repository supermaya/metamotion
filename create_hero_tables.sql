-- ================================================================
-- METAMOTION: hero_text / hero_stats / problem_slides 테이블 생성
-- 서버 phpMyAdmin에서 실행하세요 (중복 실행 안전)
-- ================================================================

-- 1. hero_text 테이블
CREATE TABLE IF NOT EXISTS hero_text (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    badge_ko      VARCHAR(255) DEFAULT '',
    badge_en      VARCHAR(255) DEFAULT '',
    badge_cn      VARCHAR(255) DEFAULT '',
    title_ko      TEXT,
    title_en      TEXT,
    title_cn      TEXT,
    description_ko TEXT,
    description_en TEXT,
    description_cn TEXT,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 기본 데이터 (이미 행이 있으면 건너뜀)
INSERT INTO hero_text
    (badge_ko, badge_en, badge_cn,
     title_ko,  title_en,  title_cn,
     description_ko, description_en, description_cn)
SELECT
    '동작 기술 혁신기업', 'Motion Tech Innovator', '动作技术革新企业',
    '빅데이터로 구현하는\n실감형 교육의 미래',
    'Shaping the Future of\nImmersive Education with Big Data',
    '用大数据实现\n沉浸式教育的未来',
    '메타모션은 초정밀 동작데이터셋과 모션 콘텐츠 제작기술을 바탕으로\nHPE와 VR을 결합하여 시공간의 제약 없는 완벽한 동작의 전수를 실현합니다.',
    'METAMOTION leverages ultra-precise motion datasets and motion content production technology,\ncombining HPE and VR to enable perfect motion transfer without temporal or spatial constraints.',
    'METAMOTION以超精密动作数据集和动作内容制作技术为基础，\n结合HPE与VR，实现不受时空限制的完美动作传授。'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM hero_text LIMIT 1);

-- ----------------------------------------------------------------

-- 2. hero_stats 테이블
CREATE TABLE IF NOT EXISTS hero_stats (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    stat_order  INT NOT NULL,
    stat_value  VARCHAR(50)  DEFAULT '',
    stat_color  VARCHAR(20)  DEFAULT 'slate',
    label_ko    VARCHAR(255) DEFAULT '',
    label_en    VARCHAR(255) DEFAULT '',
    label_cn    VARCHAR(255) DEFAULT '',
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 기본 데이터 (이미 행이 있으면 건너뜀)
INSERT INTO hero_stats (stat_order, stat_value, stat_color, label_ko, label_en, label_cn)
SELECT * FROM (
    SELECT 1 AS stat_order, '2021'  AS stat_value, 'slate'  AS stat_color, '설립연도'          AS label_ko, 'Founded'                  AS label_en, '成立年份'   AS label_cn
    UNION ALL
    SELECT 2, '48+',  'blue',   'HPE 데이터셋',          'HPE Datasets',            'HPE数据集'
    UNION ALL
    SELECT 3, '120+', 'orange', 'VR 동작학습 콘텐츠',     'VR Motion Learning Content', 'VR动作内容'
    UNION ALL
    SELECT 4, '100K', 'slate',  'App Downloads (Pre)',   'App Downloads (Pre)',      'App下载量(预)'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM hero_stats LIMIT 1);

-- ----------------------------------------------------------------

-- 3. problem_slides 테이블
CREATE TABLE IF NOT EXISTS problem_slides (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    slide_order     INT NOT NULL,
    title_ko        VARCHAR(255) DEFAULT '',
    title_en        VARCHAR(255) DEFAULT '',
    title_cn        VARCHAR(255) DEFAULT '',
    description_ko  TEXT,
    description_en  TEXT,
    description_cn  TEXT,
    image_url_ko    TEXT,
    image_url_en    TEXT,
    image_url_cn    TEXT,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ================================================================
-- 완료 확인
-- ================================================================
SELECT 'hero_text'     AS 테이블, COUNT(*) AS 행수 FROM hero_text
UNION ALL
SELECT 'hero_stats'    AS 테이블, COUNT(*) AS 행수 FROM hero_stats
UNION ALL
SELECT 'problem_slides'AS 테이블, COUNT(*) AS 행수 FROM problem_slides;
