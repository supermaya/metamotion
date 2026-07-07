@echo off
chcp 65001 > nul
cls
echo ========================================
echo  🔍 중국어 페이지 진단 도구
echo ========================================
echo.

cd /d "%~dp0"

REM PHP 경로 찾기
set PHP_PATH=
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" set PHP_PATH=C:\wamp64\bin\php\php8.2.0\php.exe
if exist "C:\wamp64\bin\php\php8.1.0\php.exe" set PHP_PATH=C:\wamp64\bin\php\php8.1.0\php.exe
if exist "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe

if "%PHP_PATH%"=="" (
    echo [❌] PHP를 찾을 수 없습니다!
    echo.
    echo XAMPP, WAMP, 또는 Laragon을 설치해주세요.
    echo.
    pause
    exit /b 1
)

echo [✅] PHP 발견: %PHP_PATH%
echo [✅] 서버 시작 중...
echo.

set PORT=8000

echo ┌────────────────────────────────────────┐
echo │  서버가 시작되었습니다!                │
echo │  브라우저가 자동으로 열립니다...       │
echo └────────────────────────────────────────┘
echo.
echo 접속 주소:
echo   http://localhost:%PORT%/diagnose_chinese.html
echo.
echo [주의] 이 창을 닫으면 서버가 종료됩니다!
echo.

REM 브라우저 자동 실행
timeout /t 2 /nobreak > nul
start http://localhost:%PORT%/diagnose_chinese.html

REM PHP 서버 시작
"%PHP_PATH%" -S localhost:%PORT%
