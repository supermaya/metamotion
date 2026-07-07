-- ============================================================
-- 1) 현재 tech 섹션 데이터 확인
-- ============================================================
SELECT section_key,
       title_ko, description_ko,
       title_en, description_en,
       title_cn, description_cn
FROM section_images
WHERE section_key IN ('tech_bigdata_bg','tech_ai_bg','tech_vrpe_bg',
                      'infra_mocap','infra_photo','infra_tech')
ORDER BY section_key;

-- ============================================================
-- 2) tech 섹션의 description을 비워서 초기화 (선택 실행)
--    → 관리자에서 직접 입력할 값만 남기고 초기화할 때 사용
-- ============================================================
-- UPDATE section_images
-- SET description_ko = '',
--     description_en = '',
--     description_cn = ''
-- WHERE section_key IN ('tech_bigdata_bg','tech_ai_bg','tech_vrpe_bg');
