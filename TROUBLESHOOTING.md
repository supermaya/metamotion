# 이미지가 보이지 않을 때 문제 해결 가이드

## 문제: 히어로 섹션 및 다른 이미지들이 보이지 않음

---

## 🔍 1단계: 데이터 확인

### phpMyAdmin에서 실행:

```sql
-- 데이터가 있는지 확인
SELECT * FROM hero_slides;
SELECT * FROM solution_slides;
SELECT * FROM section_images;
```

### 예상 결과:

#### ✅ 데이터가 있는 경우
- 각 테이블에 데이터가 표시됨
- `title_ko`, `title_en`, `image_url_ko`, `image_url_en` 컬럼에 값이 있음

#### ❌ 데이터가 없는 경우
- "Empty set" 또는 빈 결과
- 또는 컬럼에 NULL 값만 있음

---

## 🛠️ 2단계: 문제별 해결 방법

### 문제 A: 데이터가 완전히 없는 경우

**원인**: 마이그레이션 중 기존 데이터가 삭제되었거나 초기 데이터가 없음

**해결책**: 초기 데이터 삽입

1. phpMyAdmin → playelga_metamotion 선택
2. SQL 탭 클릭
3. `insert_initial_data.sql` 파일 내용 전체 복사-붙여넣기
4. 실행 버튼 클릭

```sql
-- 또는 간단한 테스트 데이터 삽입
INSERT INTO hero_slides (slide_order, title_ko, title_en, description_ko, description_en, image_url_ko, image_url_en) VALUES
(1, '테스트 제목', 'Test Title', '테스트 설명', 'Test Description',
'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=Test+KR',
'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=Test+EN');
```

---

### 문제 B: 데이터는 있지만 이미지 URL이 비어있는 경우

**원인**: 마이그레이션 시 `image_url_ko`, `image_url_en`에 데이터가 복사되지 않음

**해결책**: 데이터 확인 및 수동 업데이트

```sql
-- 현재 image_url_ko, image_url_en 값 확인
SELECT id, title_ko, image_url_ko, image_url_en FROM hero_slides;

-- 비어있으면 placeholder 이미지로 업데이트
UPDATE hero_slides
SET image_url_ko = 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=Hero+1+KR',
    image_url_en = 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=Hero+1+EN'
WHERE id = 1;
```

---

### 문제 C: 데이터도 있고 이미지 URL도 있는데 안 보이는 경우

**원인**:
1. API 호출 오류
2. 프론트엔드 JavaScript 오류
3. 브라우저 캐시

**해결책**:

#### 1) API 테스트
브라우저에서 직접 접속:
```
http://yourdomain.com/api.php?type=hero&lang=ko
```

예상 응답:
```json
{
  "slides": [
    {
      "id": 1,
      "slide_order": 1,
      "title": "실감형 VR 교육",
      "description": "몰입형 3D 레슨...",
      "image_url": "https://...",
      "updated_at": "..."
    }
  ]
}
```

**API 오류가 나는 경우**:
- 에러 메시지 확인
- `api.php` 파일의 33번째 줄 확인 (언어 파라미터 처리)
- 데이터베이스 연결 확인

#### 2) 브라우저 개발자 도구 확인
1. 페이지에서 F12 키 누르기
2. **Console 탭** 확인:
   - JavaScript 오류 메시지 확인
   - "API에서 데이터 로드 시작..." 메시지 확인
   - "Hero Slides 로드 완료" 메시지 확인

3. **Network 탭** 확인:
   - `api.php?type=hero&lang=ko` 요청 찾기
   - Status가 200 OK인지 확인
   - Response 데이터 확인

#### 3) 브라우저 캐시 삭제
- **Ctrl + F5** (강력 새로고침)
- 또는 브라우저 설정에서 캐시 삭제

---

### 문제 D: 한국어 페이지는 되는데 영어 페이지가 안 되는 경우 (또는 반대)

**원인**: 특정 언어의 이미지 URL이 비어있음

**해결책**:

```sql
-- 영어 이미지 URL 확인
SELECT id, title_en, image_url_en FROM hero_slides;

-- 비어있으면 업데이트
UPDATE hero_slides
SET image_url_en = 'https://via.placeholder.com/1920x1080/1e3a8a/FFFFFF?text=Hero+EN'
WHERE image_url_en IS NULL OR image_url_en = '';
```

---

## 📋 3단계: 전체 시스템 확인 체크리스트

### ✅ 데이터베이스
- [ ] 테이블 구조가 올바른가? (`title_ko`, `title_en` 등)
- [ ] 데이터가 존재하는가?
- [ ] `image_url_ko`, `image_url_en`에 값이 있는가?

### ✅ API
- [ ] `api.php?type=hero&lang=ko` 접속 시 JSON 응답이 오는가?
- [ ] `api.php?type=hero&lang=en` 접속 시 JSON 응답이 오는가?
- [ ] 응답에 `image_url` 필드가 포함되어 있는가?

### ✅ 프론트엔드
- [ ] 브라우저 콘솔에 에러가 없는가?
- [ ] Network 탭에서 API 호출이 성공하는가? (200 OK)
- [ ] `index.html`의 805번째 줄: `api.php?type=hero&lang=ko`
- [ ] `index_en.html`의 802번째 줄: `api.php?type=hero&lang=en`

---

## 🔧 4단계: 빠른 테스트

### 간단한 테스트 데이터로 확인:

```sql
-- 모든 데이터 삭제하고 새로 시작
TRUNCATE TABLE hero_slides;
TRUNCATE TABLE solution_slides;
TRUNCATE TABLE section_images;

-- 테스트 데이터 1개만 삽입
INSERT INTO hero_slides (slide_order, title_ko, title_en, description_ko, description_en, image_url_ko, image_url_en) VALUES
(1,
 '테스트 제목',
 'Test Title',
 '테스트 설명입니다',
 'This is test description',
 'https://via.placeholder.com/1920x1080/FF0000/FFFFFF?text=KOREAN+TEST',
 'https://via.placeholder.com/1920x1080/0000FF/FFFFFF?text=ENGLISH+TEST');

-- 확인
SELECT * FROM hero_slides;
```

이후:
1. 브라우저 캐시 삭제 (Ctrl + F5)
2. `index.html` 접속 → 빨간색 이미지에 "KOREAN TEST" 표시되어야 함
3. `index_en.html` 접속 → 파란색 이미지에 "ENGLISH TEST" 표시되어야 함

---

## 🆘 그래도 안 되는 경우

### 로그 확인:

1. **PHP 에러 로그 확인**
   - `error_log` 파일 위치 확인
   - phpMyAdmin 또는 호스팅 제어판에서 확인

2. **MySQL 쿼리 로그 확인**
   ```sql
   SHOW VARIABLES LIKE 'general_log%';
   ```

3. **API 직접 테스트**
   ```php
   // test_api.php 파일 생성
   <?php
   require_once 'config.php';
   $pdo = getDBConnection();
   $stmt = $pdo->prepare("SELECT * FROM hero_slides");
   $stmt->execute();
   $data = $stmt->fetchAll();
   echo '<pre>';
   print_r($data);
   echo '</pre>';
   ?>
   ```

---

## 📞 추가 지원 필요 시

다음 정보를 제공해주세요:
1. `check_data.sql` 실행 결과 (스크린샷)
2. 브라우저 콘솔 에러 메시지
3. `api.php?type=hero&lang=ko` 접속 결과
4. Network 탭 스크린샷

---

## 🎯 권장 순서

1. ✅ `check_data.sql` 실행 → 데이터 확인
2. ✅ 데이터 없으면 → `insert_initial_data.sql` 실행
3. ✅ API 테스트 → 브라우저에서 직접 API URL 접속
4. ✅ 브라우저 캐시 삭제 → Ctrl + F5
5. ✅ 페이지 확인 → `index.html`, `index_en.html`

이 순서대로 진행하면 대부분의 문제가 해결됩니다!
