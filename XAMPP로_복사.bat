@echo off
chcp 65001 > nul
cls
echo ========================================
echo  XAMPP htdocs로 프로젝트 복사
echo ========================================
echo.

REM XAMPP 설치 확인
if not exist "C:\xampp\htdocs" (
    echo [❌] XAMPP가 설치되어 있지 않습니다!
    echo.
    echo C:\xampp\htdocs 폴더를 찾을 수 없습니다.
    echo XAMPP를 설치하거나 "진단도구_실행.bat"을 사용하세요.
    echo.
    pause
    exit /b 1
)

echo [✅] XAMPP 발견: C:\xampp
echo.

REM 현재 디렉토리
set SOURCE=%~dp0
set DEST=C:\xampp\htdocs\metamotion

echo 복사 중...
echo   출발: %SOURCE%
echo   도착: %DEST%
echo.

REM 기존 폴더 삭제 (있다면)
if exist "%DEST%" (
    echo [정보] 기존 폴더를 삭제합니다...
    rmdir /s /q "%DEST%"
)

REM 폴더 복사
echo [진행] 파일을 복사하는 중...
xcopy "%SOURCE%*" "%DEST%\" /E /I /Y /EXCLUDE:%SOURCE%exclude.txt > nul 2>&1

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo  ✅ 복사 완료!
    echo ========================================
    echo.
    echo XAMPP Control Panel에서:
    echo  1. Apache "Start" 클릭
    echo  2. MySQL "Start" 클릭
    echo.
    echo 그 다음 브라우저에서 접속:
    echo   http://localhost/metamotion/diagnose_chinese.html
    echo.

    REM XAMPP Control Panel 실행 여부 물어보기
    choice /C YN /M "XAMPP Control Panel을 실행하시겠습니까?"
    if errorlevel 2 goto :end
    if errorlevel 1 (
        if exist "C:\xampp\xampp-control.exe" (
            start "" "C:\xampp\xampp-control.exe"
        )
    )

    :end
    echo.
    pause
) else (
    echo.
    echo [❌] 복사 중 오류가 발생했습니다.
    echo.
    pause
    exit /b 1
)
