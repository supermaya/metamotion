# 🔍 MetaMotion 중국어 페이지 진단 도구

중국어 메인 페이지(index_cn.html)에서 한글이 표시되는 문제를 해결하기 위한 종합 진단 시스템입니다.

---

## 🚀 빠른 시작 (3초 안에!)

### 가장 쉬운 방법:

```
1. "진단도구_실행.bat" 파일을 더블클릭
2. 자동으로 브라우저가 열림
3. 끝!
```

**그게 전부입니다!** 😊

---

## 📦 포함된 파일 목록

### 🎯 실행 파일 (.bat)

| 파일명 | 용도 | 추천도 |
|--------|------|--------|
| **진단도구_실행.bat** | 진단 도구만 바로 실행 | ⭐⭐⭐⭐⭐ |
| **START_SERVER.bat** | 모든 페이지 링크 표시 | ⭐⭐⭐⭐ |
| **XAMPP로_복사.bat** | XAMPP 사용자용 | ⭐⭐⭐ |

### 🔍 진단 도구 (HTML/PHP)

| 파일명 | 용도 | 언제 사용? |
|--------|------|-----------|
| **diagnose_chinese.html** | 종합 진단 대시보드 | 메인 도구 |
| **direct_db_test.php** | DB 직접 쿼리 | DB 데이터 확인 |
| **debug_cn.html** | API 개별 테스트 | API 응답 확인 |
| **test_api_cn.php** | 서버 사이드 검증 | 서버 데이터 확인 |

### 📚 문서 파일

| 파일명 | 내용 |
|--------|------|
| **실행방법.txt** | 간단한 실행 가이드 |
| **중국어_문제_해결_가이드.md** | 상세한 한글 가이드 |
| **CHINESE_DEBUG_README.md** | 영문 가이드 |
| **README_진단도구.md** | 이 파일 |

### 🎨 기타

| 파일명 | 용도 |
|--------|------|
| **LAUNCHER.html** | 시각적 런처 페이지 |
| **add_chinese_fields.sql** | DB 스키마 업데이트 |

---

## 📋 사용 시나리오별 가이드

### 시나리오 1: "처음 사용해요"

```
1. "진단도구_실행.bat" 더블클릭
2. 브라우저에서 결과 확인
3. 중국어 데이터가 비어있다면 → admin.html에서 데이터 입력
4. 중국어 데이터가 있다면 → 캐시 클리어 버튼 클릭
```

### 시나리오 2: "XAMPP를 사용해요"

```
1. "XAMPP로_복사.bat" 실행
2. XAMPP Control Panel에서 Apache, MySQL 시작
3. http://localhost/metamotion/diagnose_chinese.html 접속
```

### 시나리오 3: "더 자세한 정보가 필요해요"

```
1. "START_SERVER.bat" 실행
2. 표시되는 모든 링크 확인
3. 필요한 페이지로 접속
```

### 시나리오 4: "데이터베이스를 직접 확인하고 싶어요"

```
1. 서버 실행 (위 방법 중 하나)
2. http://localhost:8000/direct_db_test.php 접속
3. 중국어 컬럼 데이터 확인
```

---

## 🎯 진단 도구 사용법

### diagnose_chinese.html (메인 도구)

**기능**:
- ✅ 한국어/영어/중국어 API 응답 동시 비교
- ✅ 데이터 유무 자동 판단
- ✅ 브라우저 캐시 상태 체크
- ✅ 한 번의 클릭으로 캐시 삭제

**사용법**:
1. 서버 실행 후 도구 열기
2. "전체 테스트 실행" 버튼 클릭
3. 결과 확인:
   - 🟥 빨간 배경 = 한국어 데이터
   - 🟦 파란 배경 = 영어 데이터
   - 🟨 노란 배경 = 중국어 데이터

**결과 해석**:

| 결과 | 의미 | 해결 방법 |
|------|------|----------|
| 중국어 칸이 비어있음 | DB에 중국어 데이터 없음 | admin.html에서 입력 |
| 중국어 데이터 있음 | 캐시 문제 | 캐시 클리어 버튼 클릭 |
| "잘못된 페이지" 경고 | index.html 보고 있음 | index_cn.html로 이동 |

---

## ⚠️ 주의사항

### ❌ 하지 말아야 할 것

1. **HTML 파일을 직접 더블클릭하지 마세요**
   ```
   ❌ diagnose_chinese.html 직접 열기
   ✅ .bat 파일로 서버 시작 후 접속
   ```

2. **서버 창을 닫지 마세요**
   ```
   검은색 CMD 창 = 서버
   창을 닫으면 서버 종료됨
   ```

3. **file:// 프로토콜 사용 금지**
   ```
   ❌ file:///D:/98_Project/...
   ✅ http://localhost:8000/...
   ```

### ✅ 해야 할 것

1. **서버를 먼저 시작**
2. **http://localhost로 접속**
3. **문제 발견 시 스크린샷 찍기**

---

## 🐛 문제 해결

### "PHP를 찾을 수 없습니다" 에러

**원인**: PHP가 설치되지 않음

**해결**:
1. XAMPP 설치: https://www.apachefriends.org/
2. 또는 WAMP: https://www.wampserver.com/
3. 또는 Laragon: https://laragon.org/

### "포트가 이미 사용 중입니다" 에러

**원인**: 8000번 포트가 사용 중

**해결**:
1. 다른 서버 종료
2. 또는 START_SERVER.bat 편집하여 포트 변경
   ```bat
   set PORT=3000  (8000 → 3000으로 변경)
   ```

### CORS 에러 발생

**원인**: file:// 프로토콜 사용

**해결**:
1. .bat 파일로 서버 실행
2. http://localhost로 접속

### 중국어 데이터가 없다고 나옴

**원인**: DB에 중국어 데이터 미입력

**해결**:
1. admin.html 접속
2. 로그인
3. Hero Slides → 중국어 섹션 → 데이터 입력
4. Save 버튼 클릭

---

## 📊 접속 URL 정리

서버 실행 후 사용 가능한 모든 URL:

### 진단 도구
```
http://localhost:8000/diagnose_chinese.html  (메인)
http://localhost:8000/direct_db_test.php     (DB 테스트)
http://localhost:8000/debug_cn.html          (API 테스트)
http://localhost:8000/test_api_cn.php        (서버 검증)
http://localhost:8000/LAUNCHER.html          (런처)
```

### 메인 페이지
```
http://localhost:8000/index_cn.html          (중국어)
http://localhost:8000/index.html             (한국어)
http://localhost:8000/index_en.html          (영어)
```

### 관리
```
http://localhost:8000/admin.html             (관리자)
```

---

## 🎓 워크플로우 예시

### 완전한 문제 해결 과정

```
1단계: 진단
  → "진단도구_실행.bat" 실행
  → diagnose_chinese.html 자동 오픈
  → "전체 테스트 실행" 클릭

2단계: 문제 파악
  → 중국어 데이터 비어있음 확인
  → 원인: DB에 중국어 미입력

3단계: 데이터 입력
  → http://localhost:8000/admin.html 접속
  → 로그인
  → Hero Slides 중국어 섹션에 데이터 입력
  → "Save Hero Slides" 클릭

4단계: 확인
  → diagnose_chinese.html 새로고침
  → 중국어 데이터 표시 확인
  → index_cn.html에서 최종 확인

5단계: 완료!
  → 중국어 페이지에 중국어 텍스트 표시됨
```

---

## ✅ 성공 체크리스트

문제가 해결되었는지 확인:

- [ ] diagnose_chinese.html에서 중국어 데이터 확인됨
- [ ] 중국어 데이터가 한국어/영어와 다른 내용임
- [ ] index_cn.html에서 중국어 텍스트 표시됨
- [ ] 페이지 새로고침 후에도 중국어 유지됨
- [ ] 브라우저 주소가 http://localhost로 시작함

모두 체크되면 성공! 🎉

---

## 📞 추가 도움

더 자세한 정보가 필요하면:

1. **중국어_문제_해결_가이드.md** - 상세 한글 가이드
2. **CHINESE_DEBUG_README.md** - 영문 가이드
3. **실행방법.txt** - 간단 요약

스크린샷을 찍어서 공유:
- diagnose_chinese.html 결과
- direct_db_test.php 결과
- 브라우저 콘솔 에러 (F12)

---

**작성일**: 2026-02-11
**버전**: 1.0
**작성자**: Claude Code
