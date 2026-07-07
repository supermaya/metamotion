-- EN/CN 상단 3개 기술 카드 title/description 설정
-- (한국어로 admin에서 입력했지만 영어/중문이 비어있는 경우)

UPDATE section_images SET
    title_en        = 'Big Data Pipeline',
    description_en  = 'Automated data collection, refinement & labeling',
    title_cn        = '大数据流水线',
    description_cn  = '数据采集·精炼·标注自动化'
WHERE section_key = 'tech_bigdata_bg';

UPDATE section_images SET
    title_en        = 'AI Analysis Algorithm',
    description_en  = 'Motion similarity evaluation model training',
    title_cn        = 'AI 分析算法',
    description_cn  = '动作相似度评估模型训练'
WHERE section_key = 'tech_ai_bg';

UPDATE section_images SET
    title_en        = 'VRPE (Pose Estimation)',
    description_en  = 'Real-time joint tracking in VR environment',
    title_cn        = 'VRPE（姿态估计）',
    description_cn  = 'VR环境中的实时关节追踪'
WHERE section_key = 'tech_vrpe_bg';
