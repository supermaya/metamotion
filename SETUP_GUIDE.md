# 메타모션 웹사이트 설정 가이드

FastComet 호스팅 환경에서 메타모션 웹사이트를 설정하는 방법을 안내합니다.

## 📋 필요한 파일 목록

- `index.html` - 메인 페이지
- `admin.html` - 관리자 페이지
- `info01_kr.html` - 개인정보처리방침
- `info02_kr.html` - 이용약관
- `config.php` - 데이터베이스 설정
- `auth.php` - 인증 처리
- `api.php` - API 엔드포인트
- `upload.php` - 이미지 업로드
- `init.sql` - 데이터베이스 초기화 스크립트

## 🚀 설치 방법

### 1. 파일 업로드

1. FastComet의 cPanel에 로그인합니다.
2. File Manager를 엽니다.
3. `public_html` 디렉토리로 이동합니다.
4. 모든 파일을 업로드합니다.

### 2. 데이터베이스 설정

#### 2.1 MySQL 데이터베이스 생성

1. cPanel에서 **MySQL Databases**를 클릭합니다.
2. "Create New Database" 섹션에서 데이터베이스 이름을 입력합니다.
   - 예: `metamotion`
3. **Create Database** 버튼을 클릭합니다.

#### 2.2 MySQL 사용자 생성

1. "MySQL Users" 섹션에서 새 사용자를 생성합니다.
   - Username: 예) `metamotion_user`
   - Password: 강력한 비밀번호 입력
2. **Create User** 버튼을 클릭합니다.

#### 2.3 사용자에게 데이터베이스 권한 부여

1. "Add User To Database" 섹션에서:
   - User: 방금 생성한 사용자 선택
   - Database: 방금 생성한 데이터베이스 선택
2. **Add** 버튼을 클릭합니다.
3. **ALL PRIVILEGES** 체크박스를 선택합니다.
4. **Make Changes** 버튼을 클릭합니다.

#### 2.4 데이터베이스 초기화

1. cPanel에서 **phpMyAdmin**을 엽니다.
2. 왼쪽 사이드바에서 생성한 데이터베이스를 선택합니다.
3. 상단 메뉴에서 **SQL** 탭을 클릭합니다.
4. `init.sql` 파일의 내용을 복사하여 붙여넣습니다.
5. **Go** 버튼을 클릭하여 실행합니다.

### 3. config.php 설정

`config.php` 파일을 열고 데이터베이스 연결 정보를 수정합니다:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');      // 생성한 데이터베이스 이름
define('DB_USER', 'your_database_username');  // 생성한 사용자 이름
define('DB_PASS', 'your_database_password');  // 사용자 비밀번호
```

### 4. 관리자 계정 생성

#### 방법 1: phpMyAdmin 사용

1. phpMyAdmin에서 `admin_users` 테이블을 선택합니다.
2. **Insert** 탭을 클릭합니다.
3. 다음 정보를 입력합니다:
   - email: 관리자 이메일 (예: `admin@metamotion.io`)
   - password_hash: 비밀번호 해시값

비밀번호 해시를 생성하려면 다음 PHP 코드를 실행하세요:

```php
<?php
echo password_hash('your_password', PASSWORD_DEFAULT);
?>
```

#### 방법 2: create_admin.php 사용

`create_admin.php` 파일을 생성하고 다음 코드를 작성합니다:

```php
<?php
require_once 'config.php';

$email = 'admin@metamotion.io';
$password = 'your_password';  // 원하는 비밀번호로 변경

try {
    $pdo = getDBConnection();
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO admin_users (email, password_hash) VALUES (?, ?)");
    $stmt->execute([$email, $password_hash]);

    echo "관리자 계정이 생성되었습니다.";
} catch (Exception $e) {
    echo "오류: " . $e->getMessage();
}
?>
```

브라우저에서 `http://your-domain.com/create_admin.php`를 실행한 후 파일을 삭제하세요.

### 5. 디렉토리 권한 설정

이미지 업로드를 위한 `uploads` 디렉토리 권한을 설정합니다:

1. File Manager에서 `public_html` 디렉토리에 `uploads` 폴더를 생성합니다.
2. `uploads` 폴더를 우클릭하고 **Change Permissions**를 선택합니다.
3. 권한을 `755`로 설정합니다.

## 📱 사용 방법

### 관리자 페이지 접속

1. 브라우저에서 `http://your-domain.com/admin.html`에 접속합니다.
2. 생성한 관리자 계정으로 로그인합니다.

### 콘텐츠 관리

#### Hero Slides (메인 슬라이드)

1. 관리자 페이지에서 "메인 슬라이드 관리" 섹션으로 이동합니다.
2. 각 슬라이드의 제목, 설명, 이미지 URL을 수정합니다.
3. **Hero Slides 저장** 버튼을 클릭합니다.

#### Solution Slides (솔루션 슬라이드)

1. "솔루션 슬라이드 관리" 섹션으로 이동합니다.
2. 각 슬라이드의 제목, 설명, 이미지 URL을 수정합니다.
3. **Solution Slides 저장** 버튼을 클릭합니다.

#### 이미지 업로드

1. "이미지 업로드" 섹션으로 이동합니다.
2. 업로드할 이미지 파일을 선택합니다.
3. **이미지 업로드** 버튼을 클릭합니다.
4. 생성된 URL을 복사하여 슬라이드에 붙여넣습니다.

## 🔒 보안 권장사항

1. **config.php 파일 보호**
   - `.htaccess` 파일을 생성하여 `config.php` 파일에 대한 직접 접근을 차단하세요:
   ```apache
   <Files "config.php">
       Order Allow,Deny
       Deny from all
   </Files>
   ```

2. **강력한 비밀번호 사용**
   - 데이터베이스 비밀번호와 관리자 비밀번호는 반드시 강력한 것으로 설정하세요.

3. **HTTPS 사용**
   - FastComet에서 제공하는 무료 SSL 인증서를 활성화하세요.

4. **정기적인 백업**
   - 데이터베이스와 업로드된 파일을 정기적으로 백업하세요.

## 🐛 문제 해결

### 데이터베이스 연결 오류

- `config.php`의 데이터베이스 정보가 올바른지 확인하세요.
- phpMyAdmin에서 데이터베이스에 접근할 수 있는지 확인하세요.

### 이미지 업로드 실패

- `uploads` 디렉토리가 존재하는지 확인하세요.
- 디렉토리 권한이 `755`로 설정되어 있는지 확인하세요.
- PHP의 `upload_max_filesize` 설정을 확인하세요.

### 로그인 실패

- 관리자 계정이 `admin_users` 테이블에 올바르게 등록되어 있는지 확인하세요.
- 비밀번호 해시가 올바르게 생성되었는지 확인하세요.

## 📞 지원

문제가 발생하면 다음을 확인하세요:

1. 브라우저 개발자 도구의 콘솔 로그
2. 서버의 PHP 에러 로그 (cPanel의 Error Log에서 확인 가능)
3. phpMyAdmin에서 데이터베이스 상태 확인

---

설정이 완료되면 `http://your-domain.com/`에서 메인 페이지를, `http://your-domain.com/admin.html`에서 관리자 페이지를 확인할 수 있습니다.
