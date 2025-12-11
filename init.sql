-- 메타모션 데이터베이스 초기화 스크립트

-- 관리자 계정 테이블
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 히어로 슬라이드 테이블
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slide_order INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 솔루션 슬라이드 테이블
CREATE TABLE IF NOT EXISTS solution_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slide_order INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 기본 관리자 계정 생성 (이메일: admin@metamotion.io, 비밀번호: admin123)
-- 비밀번호는 반드시 변경하세요!
INSERT INTO admin_users (email, password_hash) VALUES
('admin@metamotion.io', '$2y$10$YourHashedPasswordHere');

-- 히어로 슬라이드 초기 데이터
INSERT INTO hero_slides (slide_order, title, description, image_url) VALUES
(1, '실감형 VR 교육', '몰입형 3D 레슨으로 완벽한 동작 학습', 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=VR+Motion+Learning'),
(2, '국내 최대 모션캡처 스튜디오', 'OptiTrack Prime 41 카메라 48대 보유', 'https://via.placeholder.com/1920x1080/0f172a/FFFFFF?text=Motion+Capture+Studio'),
(3, 'AI 정밀 동작 분석', 'VRPE 기술로 실시간 자세 교정', 'https://via.placeholder.com/1920x1080/1e40af/FFFFFF?text=AI+Pose+Estimation');

-- 솔루션 슬라이드 초기 데이터
INSERT INTO solution_slides (slide_order, title, description, image_url) VALUES
(1, 'K-POP 댄스', '전문 안무가의 동작을 VR로 배우기', 'https://via.placeholder.com/800x600/2563eb/FFFFFF?text=K-POP+Dance'),
(2, '태권도', '정확한 품새와 겨루기 동작 학습', 'https://via.placeholder.com/800x600/1e40af/FFFFFF?text=Taekwondo'),
(3, '요가', '정확한 자세로 완벽한 동작 완성', 'https://via.placeholder.com/800x600/4f46e5/FFFFFF?text=Yoga'),
(4, '골프', '스윙 자세 분석 및 교정', 'https://via.placeholder.com/800x600/0ea5e9/FFFFFF?text=Golf');
