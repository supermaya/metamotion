-- ================================================================
-- 다국어 초기 데이터 삽입 스크립트
-- 마이그레이션 후 데이터가 없을 경우 실행하세요
-- ================================================================

-- 기존 데이터 삭제 (선택사항 - 처음부터 시작하려면 주석 해제)
-- DELETE FROM hero_slides;
-- DELETE FROM solution_slides;
-- DELETE FROM section_images;

-- ================================================================
-- 1. Hero Slides 초기 데이터 (3개)
-- ================================================================

INSERT INTO hero_slides (slide_order, title_ko, title_en, description_ko, description_en, image_url_ko, image_url_en) VALUES
(1,
 '실감형 VR 교육',
 'Immersive VR Education',
 '몰입형 3D 레슨으로 완벽한 동작 학습',
 'Perfect motion learning with immersive 3D lessons',
 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=VR+Motion+Learning+KR',
 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=VR+Motion+Learning+EN'),

(2,
 '국내 최대 모션캡처 스튜디오',
 'Korea''s Largest Motion Capture Studio',
 'OptiTrack Prime 41 카메라 48대 보유',
 'Equipped with 48 OptiTrack Prime 41 cameras',
 'https://via.placeholder.com/1920x1080/0f172a/FFFFFF?text=Motion+Capture+Studio+KR',
 'https://via.placeholder.com/1920x1080/0f172a/FFFFFF?text=Motion+Capture+Studio+EN'),

(3,
 'AI 정밀 동작 분석',
 'AI-Powered Motion Analysis',
 'VRPE 기술로 실시간 자세 교정',
 'Real-time posture correction with VRPE technology',
 'https://via.placeholder.com/1920x1080/1e40af/FFFFFF?text=AI+Pose+Estimation+KR',
 'https://via.placeholder.com/1920x1080/1e40af/FFFFFF?text=AI+Pose+Estimation+EN')
ON DUPLICATE KEY UPDATE
    title_ko = VALUES(title_ko),
    title_en = VALUES(title_en),
    description_ko = VALUES(description_ko),
    description_en = VALUES(description_en),
    image_url_ko = VALUES(image_url_ko),
    image_url_en = VALUES(image_url_en);

-- ================================================================
-- 2. Solution Slides 초기 데이터 (4개)
-- ================================================================

INSERT INTO solution_slides (slide_order, title_ko, title_en, description_ko, description_en, image_url_ko, image_url_en) VALUES
(1,
 'K-POP 댄스',
 'K-POP Dance',
 '전문 안무가의 동작을 VR로 배우기',
 'Learn choreography from professional dancers in VR',
 'https://via.placeholder.com/800x600/2563eb/FFFFFF?text=K-POP+Dance+KR',
 'https://via.placeholder.com/800x600/2563eb/FFFFFF?text=K-POP+Dance+EN'),

(2,
 '태권도',
 'Taekwondo',
 '정확한 품새와 겨루기 동작 학습',
 'Learn precise poomsae and sparring techniques',
 'https://via.placeholder.com/800x600/1e40af/FFFFFF?text=Taekwondo+KR',
 'https://via.placeholder.com/800x600/1e40af/FFFFFF?text=Taekwondo+EN'),

(3,
 '요가',
 'Yoga',
 '정확한 자세로 완벽한 동작 완성',
 'Perfect your poses with accurate guidance',
 'https://via.placeholder.com/800x600/4f46e5/FFFFFF?text=Yoga+KR',
 'https://via.placeholder.com/800x600/4f46e5/FFFFFF?text=Yoga+EN'),

(4,
 '골프',
 'Golf',
 '스윙 자세 분석 및 교정',
 'Swing analysis and correction',
 'https://via.placeholder.com/800x600/0ea5e9/FFFFFF?text=Golf+KR',
 'https://via.placeholder.com/800x600/0ea5e9/FFFFFF?text=Golf+EN')
ON DUPLICATE KEY UPDATE
    title_ko = VALUES(title_ko),
    title_en = VALUES(title_en),
    description_ko = VALUES(description_ko),
    description_en = VALUES(description_en),
    image_url_ko = VALUES(image_url_ko),
    image_url_en = VALUES(image_url_en);

-- ================================================================
-- 3. Section Images 초기 데이터 (7개)
-- ================================================================

INSERT INTO section_images (section_key, title_ko, title_en, description_ko, description_en, image_url_ko, image_url_en) VALUES
('problem_image',
 '기존방식 (Problem)',
 'Traditional Method (Problem)',
 '단방향 2D 경험의 한계',
 'Limitations of one-way 2D experience',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Problem+KR',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Problem+EN'),

('infra_mocap',
 '모션 캡쳐 스튜디오',
 'Motion Capture Studio',
 'OptiTrack Prime 41 (48대)',
 'OptiTrack Prime 41 (48 units)',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Motion+Capture+KR',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Motion+Capture+EN'),

('infra_photo',
 '포토그래메트리 부스',
 'Photogrammetry Booth',
 '120대 DSLR 동기화 시스템',
 '120 DSLR synchronized system',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Photogrammetry+KR',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Photogrammetry+EN'),

('infra_tech',
 '모션 콘티 생성 시스템',
 'Motion Content Generation System',
 '모션캡쳐 기반 다이나믹 포즈 이미지 생성',
 'Motion capture-based dynamic pose image generation',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Tech+System+KR',
 'https://via.placeholder.com/600x400/1e293b/FFFFFF?text=Tech+System+EN'),

('tech_bigdata_bg',
 '빅데이터 파이프라인',
 'Big Data Pipeline',
 '배경 이미지 (선택사항)',
 'Background image (optional)',
 '',
 ''),

('tech_ai_bg',
 'AI 분석 알고리즘',
 'AI Analysis Algorithm',
 '배경 이미지 (선택사항)',
 'Background image (optional)',
 '',
 ''),

('tech_vrpe_bg',
 'VRPE (Pose Estimation)',
 'VRPE (Pose Estimation)',
 '배경 이미지 (선택사항)',
 'Background image (optional)',
 '',
 '')
ON DUPLICATE KEY UPDATE
    title_ko = VALUES(title_ko),
    title_en = VALUES(title_en),
    description_ko = VALUES(description_ko),
    description_en = VALUES(description_en),
    image_url_ko = VALUES(image_url_ko),
    image_url_en = VALUES(image_url_en);

-- ================================================================
-- 데이터 삽입 완료
-- ================================================================
SELECT '초기 데이터가 성공적으로 삽입되었습니다!' as result;
