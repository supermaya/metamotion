# 📊 MetaMotion 프로젝트 분석 보고서

> 생성일: 2026-04-18  
> 분석 대상: `c:\Project\metamotion`  
> 호스팅: **FastComet** (공유 호스팅, PHP + MySQL)

---

## 1. 프로젝트 개요

**MetaMotion**은 모션캡처 스튜디오 및 VR 동작 학습 플랫폼을 소개하는 다국어 기업 웹사이트입니다.  
관리자가 콘텐츠(슬라이드, 섹션 이미지, 텍스트)를 CMS 방식으로 직접 편집할 수 있는 구조를 갖추고 있습니다.

### 주요 특징
- **3개 언어** 지원: 한국어(`ko`) / 영어(`en`) / 중국어(`cn`)
- **SPA 유사 구조**: `spa-router.js` 기반 클라이언트 라우팅
- **자체 제작 CMS**: `admin.html`에서 모든 콘텐츠 관리
- **Firebase 미사용**: 순수 PHP + MySQL 스택 (Firebase는 개인정보처리방침 제3자 언급만)

---

## 2. 기술 스택

| 구분 | 기술 |
|------|------|
| **서버 언어** | PHP (PDO, session 기반 인증) |
| **데이터베이스** | MySQL (`playelga_metamotion`) |
| **프론트엔드** | Vanilla HTML5 + CSS + JavaScript (프레임워크 없음) |
| **라우팅** | `spa-router.js` (SPA 스타일 클라이언트 라우팅) |
| **이미지 업로드** | PHP `move_uploaded_file()` → `uploads/` 디렉토리 |
| **인증** | PHP Session + `password_hash()` / `password_verify()` |
| **호스팅** | FastComet 공유 호스팅 |
| **웹서버 설정** | Apache `.htaccess` (URL 재작성 포함) |

---

## 3. 파일 통계

### 3-1. 파일 수 및 용량 (전체)

| 구분 | 수치 |
|------|------|
| **총 파일 수** | 67개 |
| **총 용량** | 27.76 MB |

### 3-2. 확장자별 분류

| 확장자 | 파일 수 | 용량 (KB) | 설명 |
|--------|---------|-----------|------|
| `.html` | 14 | 490 | 프론트엔드 페이지 |
| `.sql` | 12 | 31.6 | DB 스키마 / 마이그레이션 |
| `.다운로드` | 12 | 27,587 | 외부 캡처 파일 (참조용) |
| `.md` | 10 | 62.6 | 문서 파일 |
| `.php` | 9 | 37.8 | 백엔드 로직 |
| `.bat` | 3 | 5.1 | 로컬 실행 스크립트 |
| `.css` | 2 | 203 | 스타일시트 |
| `.js` | 1 | 4.7 | SPA 라우터 |
| `.htaccess` | 1 | 2.4 | Apache 설정 |
| `.txt` | 1 | 5.2 | 실행방법 가이드 |

> ⚠️ `.다운로드` 파일(27.5MB)은 외부 캡처 번들로 실제 서비스 코드가 아닙니다.  
> **실제 서비스 코드 용량은 약 800KB** 수준입니다.

---

## 4. 디렉토리 구조

```
metamotion/
│
├── 📄 프론트엔드 (HTML)
│   ├── index.html          # 메인 (한국어)
│   ├── index_cn.html       # 메인 (중국어)
│   ├── index_en.html       # 메인 (영어)
│   ├── info01_kr.html      # 개인정보처리방침 (한국어)
│   ├── info01_en.html      # 개인정보처리방침 (영어)
│   ├── info01_cn.html      # 개인정보처리방침 (중국어)
│   ├── info02_kr.html      # 이용약관 (한국어)
│   ├── info02_en.html      # 이용약관 (영어)
│   ├── info02_cn.html      # 이용약관 (중국어)
│   ├── admin.html          # 관리자 CMS (통합)
│   ├── admin_backup.html   # 관리자 백업본
│   ├── LAUNCHER.html       # 로컬 실행용 런처
│   ├── debug_cn.html       # 중국어 디버그 도구
│   └── diagnose_chinese.html  # 중국어 진단 도구
│
├── 📄 백엔드 (PHP)
│   ├── config.php          # DB 연결 설정
│   ├── api.php             # 메인 REST API (593줄)
│   ├── auth.php            # 인증 API (로그인/로그아웃/세션)
│   ├── upload.php          # 이미지 업로드 처리
│   ├── create_admin.php    # 관리자 계정 초기 생성
│   ├── reset_password.php  # 비밀번호 초기화 (임시)
│   ├── direct_db_test.php  # DB 연결 테스트
│   └── test_api.php / test_api_cn.php  # API 테스트
│
├── 📄 데이터베이스 (SQL)
│   ├── init.sql                    # DB 초기 생성
│   ├── migration_multilang.sql     # 다국어 마이그레이션
│   ├── migration_safe.sql          # 안전한 마이그레이션 (중복 방지)
│   ├── add_chinese_fields.sql      # 중국어 필드 추가
│   ├── insert_initial_data.sql     # 초기 데이터
│   ├── reset_all_data.sql          # 전체 데이터 초기화
│   ├── update_schema.sql           # 스키마 업데이트
│   ├── check_tables.sql            # 테이블 확인
│   ├── check_all_tables.sql        # 전체 테이블 확인
│   ├── check_data.sql              # 데이터 확인
│   ├── debug_en_images.sql         # 영문 이미지 디버그
│   └── diagnose_english_images.sql # 영문 이미지 진단
│
├── 📄 설정 / 서버
│   ├── config.php          # DB 자격증명
│   ├── .env.local          # 환경변수 (로컬용)
│   ├── .htaccess           # Apache URL 재작성 규칙
│   └── spa-router.js       # 클라이언트 SPA 라우터
│
├── 📁 image/               # (현재 비어있음)
├── 📁 info01_en_files/     # 영문 info 페이지 첨부 파일
├── 📁 info02_en_files/     # 영문 info 페이지 첨부 파일
│
└── 📄 문서 (MD / TXT)
    ├── PROJECT_SUMMARY.md
    ├── PROJECT_ANALYSIS.md  ← 이 파일
    ├── README_MULTILANG.md
    ├── ADMIN_GUIDE.md
    ├── MIGRATION_GUIDE.md
    ├── FINAL_SETUP_GUIDE.md
    ├── SETUP_GUIDE.md
    ├── TROUBLESHOOTING.md
    ├── CHINESE_DEBUG_README.md
    ├── 중국어_문제_해결_가이드.md
    └── 실행방법.txt
```

---

## 5. 데이터베이스 스키마

### DB 접속 정보
| 항목 | 값 |
|------|-----|
| Host | `localhost` |
| Database | `playelga_metamotion` |
| User | `playelga_metamotion` |
| Charset | `utf8mb4` |

### 테이블 목록

#### `admin_users` — 관리자 계정
| 컬럼 | 타입 | 설명 |
|------|------|------|
| `id` | INT AUTO_INCREMENT | PK |
| `email` | VARCHAR(255) UNIQUE | 로그인 이메일 |
| `password_hash` | VARCHAR(255) | `password_hash()` 해시 |
| `created_at` | TIMESTAMP | 생성일 |

#### `hero_slides` — 메인 히어로 슬라이드 (최대 3개)
| 컬럼 | 타입 |
|------|------|
| `id` | INT PK |
| `slide_order` | INT |
| `title_ko` / `title_en` / `title_cn` | VARCHAR(255) |
| `description_ko` / `description_en` / `description_cn` | TEXT |
| `image_url_ko` / `image_url_en` / `image_url_cn` | TEXT |
| `updated_at` | TIMESTAMP |

#### `solution_slides` — 솔루션 섹션 슬라이드
> `hero_slides`와 동일한 다국어 컬럼 구조

#### `avatar_slides` — 아바타 섹션 슬라이드
> `_ko` / `_en` 컬럼 구조 (중국어 미포함)

#### `content_slides` — 콘텐츠 섹션 슬라이드
> `_ko` / `_en` 컬럼 구조

#### `saas_slides` — SaaS 섹션 슬라이드
> `_ko` / `_en` 컬럼 구조

#### `section_images` — 고정 섹션 이미지 (7개)
| 컬럼 | 타입 | 설명 |
|------|------|------|
| `id` | INT PK | |
| `section_key` | VARCHAR UNIQUE | 섹션 식별자 |
| `title_*` / `description_*` / `image_url_*` | 다국어 | `_ko` / `_en` / `_cn` |
| `updated_at` | TIMESTAMP | |

---

## 6. API 구조 (`api.php`)

REST API 방식으로 동작하며 `?type=&action=&lang=` 쿼리 파라미터 조합으로 라우팅합니다.

### GET — 데이터 조회 (공개)

| 엔드포인트 | 설명 |
|-----------|------|
| `api.php?type=hero&lang=ko` | 히어로 슬라이드 (한국어) |
| `api.php?type=hero&lang=en` | 히어로 슬라이드 (영어) |
| `api.php?type=hero&lang=cn` | 히어로 슬라이드 (중국어) |
| `api.php?type=solution&lang=*` | 솔루션 슬라이드 |
| `api.php?type=avatar&lang=*` | 아바타 슬라이드 |
| `api.php?type=content&lang=*` | 콘텐츠 슬라이드 |
| `api.php?type=saas&lang=*` | SaaS 슬라이드 |
| `api.php?type=sections&lang=*` | 섹션 이미지 |

### GET — 데이터 조회 (관리자 전용, 세션 필요)

| 엔드포인트 | 설명 |
|-----------|------|
| `api.php?type=hero_admin` | 전 언어 히어로 데이터 |
| `api.php?type=solution_admin` | 전 언어 솔루션 데이터 |
| `api.php?type=sections_admin` | 전 언어 섹션 데이터 |
| `api.php?type=avatar_admin` | 전 언어 아바타 데이터 |
| `api.php?type=content_admin` | 전 언어 콘텐츠 데이터 |
| `api.php?type=saas_admin` | 전 언어 SaaS 데이터 |

### POST — 데이터 저장 (관리자 전용)

| 엔드포인트 | 설명 |
|-----------|------|
| `api.php?type=hero&action=save` | 히어로 슬라이드 저장 (최대 3개) |
| `api.php?type=solution&action=save` | 솔루션 저장 |
| `api.php?type=avatar&action=save` | 아바타 저장 |
| `api.php?type=content&action=save` | 콘텐츠 저장 |
| `api.php?type=saas&action=save` | SaaS 저장 |
| `api.php?type=sections&action=save` | 섹션 이미지 저장 (UPSERT) |

### 인증 API (`auth.php`)

| 엔드포인트 | 메서드 | 설명 |
|-----------|--------|------|
| `auth.php?action=login` | POST | 로그인 (이메일 + 비밀번호) |
| `auth.php?action=logout` | POST | 로그아웃 |
| `auth.php?action=check` | GET | 세션 확인 |
| `auth.php?action=create_admin` | POST | 신규 관리자 생성 |

---

## 7. 다국어(i18n) 구조

| 언어 | 파일 | API 파라미터 |
|------|------|-------------|
| 한국어 | `index.html` | `lang=ko` |
| 영어 | `index_en.html` | `lang=en` |
| 중국어 | `index_cn.html` | `lang=cn` |

- 각 언어 페이지가 API 호출 시 해당 언어 파라미터를 지정
- DB 컬럼에 언어별 값 직접 저장 (`title_ko`, `title_en`, `title_cn`)
- 중국어는 `hero_slides`, `solution_slides`, `section_images`에만 `_cn` 컬럼 적용
- `avatar_slides`, `content_slides`, `saas_slides`는 `_ko` / `_en`만 지원

---

## 8. 이미지 업로드 (`upload.php`)

| 항목 | 내용 |
|------|------|
| 저장 위치 | `uploads/` 디렉토리 (자동 생성) |
| 허용 형식 | `jpg`, `jpeg`, `png`, `gif`, `webp` |
| 최대 용량 | 10MB |
| 파일명 규칙 | `{timestamp}_{uniqid}.{ext}` |
| 인증 요구 | ✅ 세션 필요 |

---

## 9. 보안 현황

| 항목 | 상태 | 비고 |
|------|------|------|
| 비밀번호 해싱 | ✅ `password_hash()` / `password_verify()` | PHP 표준 bcrypt |
| SQL Injection 방지 | ✅ PDO Prepared Statement | 전 쿼리 적용 |
| 관리자 API 인증 | ✅ 세션 체크 (`checkAuth()`) | |
| CORS 설정 | ⚠️ `Access-Control-Allow-Origin: *` | 와일드카드 — 프로덕션 제한 권장 |
| XSS 방지 | ⚠️ 부분적 (`htmlspecialchars`) | 일부 출력만 적용 |
| HTTPS | ✅ FastComet 자동 적용 | `.htaccess`에서 처리 |
| `reset_password.php` | ⚠️ 보안 키 적용됨 | **사용 후 즉시 삭제 필요** |

---

## 10. 알려진 이슈 및 개선 제안

### 🔴 즉시 조치 필요
1. **`reset_password.php` 삭제** — 비밀번호 초기화 후 서버에서 반드시 제거
2. **CORS 제한** — `Access-Control-Allow-Origin: *` → 실제 도메인으로 변경
   ```php
   header('Access-Control-Allow-Origin: https://metamotion.io');
   ```

### 🟡 단기 개선 권장
3. **`avatar_slides` / `content_slides` / `saas_slides` 중국어 지원 추가**  
   현재 `hero_slides`, `solution_slides`, `section_images`만 `_cn` 컬럼 보유
4. **SPA 라우터 중복 선언 버그** — `currentSlide` 변수 중복으로 콘솔 에러 발생
5. **이미지 최적화** — WebP 변환, Lazy Loading, srcset 적용
6. **API 응답 캐싱** — `Cache-Control` 헤더 추가로 반복 요청 감소

### 🟢 장기 개선 사항
7. **언어 자동 감지** — 브라우저 `navigator.language` 기반 자동 리다이렉트
8. **이미지 CDN 연동** — FastComet 서버 직접 서빙 대신 CDN 사용
9. **Admin 대시보드 개선** — 드래그앤드롭 슬라이드 순서 변경

---

## 11. 로컬 개발 환경

| 도구 | 설명 |
|------|------|
| **XAMPP** | 로컬 PHP + MySQL 서버 (`XAMPP로_복사.bat`) |
| **START_SERVER.bat** | 로컬 서버 시작 스크립트 |
| **LAUNCHER.html** | 로컬 브라우저 진입점 |
| **진단도구_실행.bat** | 중국어 진단 도구 실행 |

---

## 12. 배포 체크리스트 (FastComet)

- [ ] `config.php` DB 자격증명 확인
- [ ] `uploads/` 디렉토리 권한 `755` 설정
- [ ] `.env.local` 파일 업로드 제외
- [ ] `reset_password.php` / `create_admin.php` / `test_api*.php` 삭제
- [ ] `.htaccess` HTTPS 리다이렉트 활성화 확인
- [ ] 모든 SQL 마이그레이션 phpMyAdmin에서 실행 완료
- [ ] 관리자 계정 비밀번호 기본값에서 변경
- [ ] CORS 도메인 제한 설정

---

*이 문서는 Antigravity AI에 의해 자동 분석 생성되었습니다.*
