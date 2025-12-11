# 다국어 이미지 시스템 구현 가이드

## 개요
이 프로젝트는 한국어와 영어 두 가지 언어를 지원하며, 각 언어별로 다른 이미지를 표시할 수 있도록 업그레이드되었습니다.

## 변경 사항

### 1. 데이터베이스 스키마 업데이트 ✅
**파일**: `migration_multilang.sql`

모든 테이블에 언어별 컬럼이 추가되었습니다:
- `hero_slides`: `title_ko`, `title_en`, `description_ko`, `description_en`, `image_url_ko`, `image_url_en`
- `solution_slides`: 동일한 구조
- `section_images`: 동일한 구조

**마이그레이션 실행 방법**:
```bash
mysql -u playelga_metamotion -p playelga_metamotion < migration_multilang.sql
```

⚠️ **중요**: 마이그레이션 전 반드시 데이터베이스를 백업하세요!

### 2. API 엔드포인트 업데이트 ✅
**파일**: `api.php`

#### 새로운 기능:
- **언어 파라미터 지원**: `?lang=ko` 또는 `?lang=en`
- **프론트엔드용 엔드포인트**: 선택된 언어의 데이터만 반환
  - `api.php?type=hero&lang=ko`
  - `api.php?type=solution&lang=en`
  - `api.php?type=sections&lang=ko`

- **관리자용 엔드포인트**: 모든 언어 데이터 반환
  - `api.php?type=hero_admin`
  - `api.php?type=solution_admin`
  - `api.php?type=sections_admin`

### 3. 프론트엔드 페이지 업데이트 ✅
**파일**: `index.html`, `index_en.html`

각 페이지는 해당 언어의 이미지를 로드합니다:
- **한국어 페이지** (`index.html`): `api.php?type=hero&lang=ko`
- **영어 페이지** (`index_en.html`): `api.php?type=hero&lang=en`

### 4. 관리자 페이지 업데이트 ✅

#### 한국어 관리자 페이지: `admin.html`
- 언어별 입력 필드 구분 (파란색: 한국어, 초록색: 영어)
- 양쪽 언어의 이미지를 동시에 관리
- Hero Slides, Solution Slides, Section Images 모두 지원

#### 영어 관리자 페이지: `admin_en.html`
- 동일한 기능, 영어 인터페이스

### 5. 백업 파일 생성 ✅
**파일**: `admin_backup.html`
- 원본 admin.html의 백업 (마이그레이션 전 버전)

## 사용 방법

### 관리자 작업 흐름

1. **로그인**
   - `admin.html` (한국어) 또는 `admin_en.html` (영어) 접속
   - 기존 관리자 계정으로 로그인

2. **이미지 업로드**
   - 페이지 하단의 "이미지 업로드" 섹션 사용
   - 파일 선택 후 업로드
   - 생성된 URL 복사

3. **콘텐츠 관리**
   - **Hero Slides (메인 슬라이드)**:
     - 최대 3개 고정
     - 각 슬라이드마다 한국어/영어 버전 입력
       - 제목 (한국어/영어)
       - 설명 (한국어/영어)
       - 이미지 URL (한국어/영어)

   - **Solution Slides (솔루션 슬라이드)**:
     - 개수 제한 없음
     - 동일한 구조로 입력

   - **Section Images (섹션 이미지)**:
     - 7개 고정 섹션
     - 각 섹션마다 한국어/영어 이미지 URL 입력

4. **저장**
   - 각 섹션의 "저장" 버튼 클릭
   - 성공 메시지 확인

### 사용자 경험

- **한국어 사용자**: `index.html` 방문 시 한국어 텍스트와 이미지 표시
- **영어 사용자**: `index_en.html` 방문 시 영어 텍스트와 이미지 표시

## 파일 구조

```
D:\98_Project\metamotion\
├── migration_multilang.sql      # 데이터베이스 마이그레이션 스크립트
├── api.php                       # 업데이트된 API (언어 파라미터 지원)
├── index.html                    # 한국어 프론트엔드 (lang=ko)
├── index_en.html                 # 영어 프론트엔드 (lang=en)
├── admin.html                    # 한국어 관리자 페이지
├── admin_en.html                 # 영어 관리자 페이지
├── admin_backup.html             # 원본 admin.html 백업
├── init.sql                      # 기존 초기화 스크립트 (참고용)
├── update_schema.sql             # 기존 업데이트 스크립트 (참고용)
└── README_MULTILANG.md           # 이 문서
```

## API 레퍼런스

### GET 요청 (프론트엔드)

```http
GET /api.php?type=hero&lang=ko
GET /api.php?type=hero&lang=en
GET /api.php?type=solution&lang=ko
GET /api.php?type=solution&lang=en
GET /api.php?type=sections&lang=ko
GET /api.php?type=sections&lang=en
```

**응답 예시**:
```json
{
  "slides": [
    {
      "id": 1,
      "slide_order": 1,
      "title": "실감형 VR 교육",
      "description": "몰입형 3D 레슨으로 완벽한 동작 학습",
      "image_url": "https://example.com/image-ko.jpg",
      "updated_at": "2025-12-10 12:00:00"
    }
  ]
}
```

### GET 요청 (관리자)

```http
GET /api.php?type=hero_admin
GET /api.php?type=solution_admin
GET /api.php?type=sections_admin
```

**응답 예시** (모든 언어 데이터 포함):
```json
{
  "slides": [
    {
      "id": 1,
      "slide_order": 1,
      "title_ko": "실감형 VR 교육",
      "title_en": "Immersive VR Education",
      "description_ko": "몰입형 3D 레슨으로 완벽한 동작 학습",
      "description_en": "Perfect motion learning with immersive 3D lessons",
      "image_url_ko": "https://example.com/image-ko.jpg",
      "image_url_en": "https://example.com/image-en.jpg",
      "updated_at": "2025-12-10 12:00:00"
    }
  ]
}
```

### POST 요청 (저장)

```http
POST /api.php?type=hero&action=save
Content-Type: application/json

{
  "slides": [
    {
      "title_ko": "한국어 제목",
      "title_en": "English Title",
      "description_ko": "한국어 설명",
      "description_en": "English Description",
      "image_url_ko": "https://example.com/ko.jpg",
      "image_url_en": "https://example.com/en.jpg"
    }
  ]
}
```

## 주의사항

1. **데이터베이스 마이그레이션**:
   - 마이그레이션 실행 전 반드시 백업
   - 기존 데이터는 자동으로 한국어 컬럼으로 복사됨
   - 영어 데이터는 초기값으로 "(English version)" 접미사가 추가됨

2. **이미지 URL 관리**:
   - 각 언어별로 다른 이미지 URL 사용 권장
   - 동일한 이미지를 사용하려면 양쪽 필드에 같은 URL 입력

3. **인증**:
   - 모든 저장/업데이트 작업은 관리자 인증 필요
   - 로그인 없이는 데이터 조회만 가능

4. **브라우저 캐시**:
   - 변경 사항이 반영되지 않으면 브라우저 캐시 삭제 (Ctrl+F5)

## 문제 해결

### 이미지가 표시되지 않는 경우
1. 이미지 URL이 올바른지 확인
2. 이미지 파일이 실제로 업로드되었는지 확인
3. 브라우저 개발자 도구에서 네트워크 오류 확인

### 저장이 안 되는 경우
1. 로그인 상태 확인
2. 브라우저 콘솔에서 오류 메시지 확인
3. 데이터베이스 마이그레이션이 완료되었는지 확인

### 언어가 잘못 표시되는 경우
1. URL이 올바른지 확인 (`index.html` vs `index_en.html`)
2. API 호출 시 `lang` 파라미터 확인
3. 브라우저 개발자 도구 네트워크 탭에서 API 응답 확인

## 업데이트 이력

- **2025-12-10**: 다국어 이미지 시스템 구현 완료
  - 데이터베이스 스키마 업데이트
  - API 언어 파라미터 지원 추가
  - 프론트엔드 언어별 로드 로직 구현
  - 관리자 페이지 다국어 관리 기능 추가
  - 영문 관리자 페이지 생성

## 라이센스
© 2024 METAMOTION. All rights reserved.
