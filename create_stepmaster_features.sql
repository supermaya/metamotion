CREATE TABLE IF NOT EXISTS stepmaster_features (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    feat_order   INT NOT NULL DEFAULT 1,
    title_ko     VARCHAR(255) DEFAULT '',
    title_en     VARCHAR(255) DEFAULT '',
    title_cn     VARCHAR(255) DEFAULT '',
    desc_ko      TEXT,
    desc_en      TEXT,
    desc_cn      TEXT,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO stepmaster_features (feat_order, title_ko, title_en, title_cn, desc_ko, desc_en, desc_cn)
SELECT 1, '초실감형 3D 레슨', 'Ultra-Realistic 3D Lessons', '超真实3D课程',
    '디지털 트윈 강사를 360도로 돌려보며, 현장감 있는 몰입 학습을 제공합니다.',
    'Rotate digital twin instructors 360 degrees for immersive, realistic learning.',
    '360度旋转数字孪生讲师，提供身临其境的沉浸式学习体验。'
WHERE (SELECT COUNT(*) FROM stepmaster_features) = 0;

INSERT INTO stepmaster_features (feat_order, title_ko, title_en, title_cn, desc_ko, desc_en, desc_cn)
SELECT 2, 'AI 정밀 코칭 (VRPE)', 'AI Precision Coaching (VRPE)', 'AI精准教练(VRPE)',
    'HPE 기술로 사용자의 관절을 추적, 전문가 동작과의 유사도를 정밀 분석합니다.',
    'HPE technology tracks your joints and precisely analyzes similarity to expert movements.',
    '利用HPE技术追踪用户关节，精准分析与专家动作的相似度。'
WHERE (SELECT COUNT(*) FROM stepmaster_features) = 1;

INSERT INTO stepmaster_features (feat_order, title_ko, title_en, title_cn, desc_ko, desc_en, desc_cn)
SELECT 3, '게이미피케이션', 'Gamification', '游戏化',
    '랭킹 경쟁, 업적 달성, 보상 시스템을 통해 훈련을 게임처럼 즐겁게 지속합니다.',
    'Ranking, achievement, and reward systems make training as fun as a game.',
    '通过排名竞争、成就达成、奖励系统，让训练像游戏一样持续进行。'
WHERE (SELECT COUNT(*) FROM stepmaster_features) = 2;
