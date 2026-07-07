@echo off
chcp 65001 > nul
echo ========================================
echo  MetaMotion 진단 도구 서버 시작
echo ========================================
echo.

REM 현재 디렉토리로 이동
cd /d "%~dp0"

REM PHP 경로 찾기
set PHP_PATH=
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" set PHP_PATH=C:\wamp64\bin\php\php8.2.0\php.exe
if exist "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe

REM PHP 경로 확인
if "%PHP_PATH%"=="" (
    echo [오류] PHP를 찾을 수 없습니다!
    echo.
    echo 다음 중 하나를 설치해주세요:
    echo  - XAMPP: https://www.apachefriends.org/
    echo  - WAMP: https://www.wampserver.com/
    echo  - Laragon: https://laragon.org/
    echo.
    pause
    exit /b 1
)

echo [✓] PHP 발견: %PHP_PATH%
echo [✓] 프로젝트 경로: %CD%
echo.

REM 포트 설정
set PORT=8000

echo [시작] 서버를 포트 %PORT%에서 시작합니다...
echo.
echo ========================================
echo  서버 실행 중...
echo ========================================
echo.
echo 브라우저에서 다음 주소로 접속하세요:
echo.
echo  📊 진단 도구:
echo     http://localhost:%PORT%/diagnose_chinese.html
echo.
echo  🌐 중국어 페이지:
echo     http://localhost:%PORT%/index_cn.html
echo.
echo  ⚙️  관리자 페이지:
echo     http://localhost:%PORT%/admin.html
echo.
echo ========================================
echo  서버를 중지하려면 Ctrl+C를 누르세요
echo ========================================
echo.

REM 5초 후 브라우저 자동 열기
timeout /t 3 /nobreak > nul
start http://localhost:%PORT%/diagnose_chinese.html

REM PHP 서버 시작
"%PHP_PATH%" -S localhost:%PORT%

pause
