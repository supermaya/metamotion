# 데이터베이스 마이그레이션 실행 가이드

## ⚠️ 시작하기 전에

**반드시 데이터베이스를 백업하세요!**

### phpMyAdmin에서 백업하는 방법:
1. phpMyAdmin 접속
2. 좌측에서 `playelga_metamotion` 데이터베이스 선택
3. 상단 메뉴에서 "내보내기(Export)" 클릭
4. "빠른(Quick)" 방법 선택, 형식은 "SQL"
5. "실행(Go)" 클릭하여 백업 파일 다운로드
6. 파일 이름: `playelga_metamotion_backup_2025-12-10.sql` 형식으로 저장

---

## 방법 1: phpMyAdmin 사용 (권장) 👍

### 단계 1: phpMyAdmin 접속
1. 웹 브라우저에서 phpMyAdmin 열기
   - 일반적으로: `http://localhost/phpmyadmin`
   - 또는 호스팅 제공자가 제공한 URL

2. 데이터베이스 계정으로 로그인:
   - 사용자명: `playelga_metamotion`
   - 비밀번호: `b0},&UdSc!a$0Dqe`

### 단계 2: 데이터베이스 선택
1. 좌측 패널에서 **`playelga_metamotion`** 데이터베이스 클릭
2. 선택된 데이터베이스가 진하게 표시됨

### 단계 3: SQL 실행
1. 상단 메뉴에서 **"SQL"** 탭 클릭
2. SQL 쿼리 입력 상자에 아래 내용을 붙여넣기

### 단계 4: 마이그레이션 스크립트 복사-붙여넣기
`D:\98_Project\metamotion\migration_multilang.sql` 파일을 텍스트 에디터로 열어서 **전체 내용**을 복사한 후 phpMyAdmin의 SQL 입력창에 붙여넣기

### 단계 5: 실행
1. 입력창 하단 우측의 **"실행(Go)"** 버튼 클릭
2. 성공 메시지 확인:
   - "Query OK" 또는 "쿼리가 성공적으로 실행되었습니다" 표시
3. 오류가 없는지 확인

### 단계 6: 확인
1. 좌측에서 `hero_slides` 테이블 클릭
2. "구조(Structure)" 탭에서 새로운 컬럼 확인:
   - `title_ko`
   - `title_en`
   - `description_ko`
   - `description_en`
   - `image_url_ko`
   - `image_url_en`

---

## 방법 2: 명령줄(CMD) 사용

### 사전 준비
MySQL이 PATH에 등록되어 있어야 합니다. 확인:
```cmd
mysql --version
```

### 실행 명령어

1. **CMD 또는 PowerShell 열기**
   - Windows 키 + R
   - `cmd` 입력 후 Enter

2. **프로젝트 디렉토리로 이동**
```cmd
cd D:\98_Project\metamotion
```

3. **마이그레이션 실행**
```cmd
mysql -u playelga_metamotion -p playelga_metamotion < migration_multilang.sql
```

4. **비밀번호 입력**
   - 프롬프트가 나오면: `b0},&UdSc!a$0Dqe` 입력

5. **성공 확인**
   - 오류 메시지가 없으면 성공
   - 확인 명령어:
```cmd
mysql -u playelga_metamotion -p -e "DESCRIBE hero_slides" playelga_metamotion
```

---

## 방법 3: MySQL Workbench 사용

### 단계 1: MySQL Workbench 실행
1. MySQL Workbench 프로그램 열기
2. 데이터베이스 연결 클릭 또는 새 연결 생성:
   - Hostname: `localhost`
   - Port: `3306`
   - Username: `playelga_metamotion`
   - Password: `b0},&UdSc!a$0Dqe`
   - Default Schema: `playelga_metamotion`

### 단계 2: SQL 스크립트 열기
1. 메뉴: **File > Open SQL Script**
2. `D:\98_Project\metamotion\migration_multilang.sql` 선택

### 단계 3: 실행
1. 상단 툴바에서 번개 아이콘(⚡) 클릭 또는 Ctrl + Shift + Enter
2. "Action Output" 패널에서 결과 확인

### 단계 4: 확인
1. 좌측 "Schemas" 패널에서 `playelga_metamotion` 우클릭
2. "Refresh All" 선택
3. Tables > `hero_slides` > Columns 확장하여 새 컬럼 확인

---

## 마이그레이션 후 확인 사항

### 1. 테이블 구조 확인

**hero_slides 테이블**:
```sql
SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'hero_slides'
  AND TABLE_SCHEMA = 'playelga_metamotion';
```

예상 결과:
- id
- slide_order
- title_ko
- title_en
- description_ko
- description_en
- image_url_ko
- image_url_en
- updated_at

### 2. 데이터 확인

기존 데이터가 한국어 컬럼으로 복사되었는지 확인:
```sql
SELECT title_ko, title_en FROM hero_slides LIMIT 3;
```

### 3. 모든 테이블 확인
- `hero_slides` ✓
- `solution_slides` ✓
- `section_images` ✓

---

## 문제 해결

### 오류: "Table 'xxx' doesn't exist"
- 데이터베이스가 올바르게 선택되었는지 확인
- `USE playelga_metamotion;` 명령어 먼저 실행

### 오류: "Access denied"
- 사용자명과 비밀번호가 올바른지 확인
- 데이터베이스 권한 확인

### 오류: "Duplicate column name"
- 이미 마이그레이션이 실행되었을 수 있음
- 테이블 구조를 확인하여 `_ko`, `_en` 컬럼이 이미 존재하는지 체크

### 오류: "Lost connection to MySQL server"
- MySQL 서비스가 실행 중인지 확인
- 방화벽 설정 확인

---

## 롤백 방법 (문제 발생 시)

마이그레이션 중 문제가 발생하면 백업 복원:

### phpMyAdmin에서 복원:
1. phpMyAdmin 접속
2. `playelga_metamotion` 데이터베이스 선택
3. "가져오기(Import)" 탭 클릭
4. 백업 파일 선택
5. "실행(Go)" 클릭

### 명령줄에서 복원:
```cmd
mysql -u playelga_metamotion -p playelga_metamotion < backup_file.sql
```

---

## 마이그레이션 후 다음 단계

1. ✅ 데이터베이스 마이그레이션 완료
2. 관리자 페이지 접속 (`admin.html` 또는 `admin_en.html`)
3. 언어별 콘텐츠 입력:
   - 한국어 이미지 URL 입력
   - 영어 이미지 URL 입력
4. 저장 및 테스트:
   - `index.html` 접속 → 한국어 이미지 확인
   - `index_en.html` 접속 → 영어 이미지 확인

---

## 지원

문제가 발생하면:
1. 오류 메시지를 정확히 복사
2. 어느 단계에서 오류가 발생했는지 기록
3. 데이터베이스 로그 확인
