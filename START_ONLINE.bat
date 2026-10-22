@echo off
title ColdDrinkFund - 24/7 Live Online Server
echo ===================================================
echo   ColdDrinkFund - Starting 24/7 Online Server
echo ===================================================
echo.

cd /d "%~dp0"

echo [1/2] Starting local PHP server on port 8000...
start "" /b php -S 127.0.0.1:8000 server.php

timeout /t 2 /nobreak >nul

echo [2/2] Starting Cloudflare Tunnel...
echo.
echo Your public live HTTPS link will appear below:
echo ===================================================
cloudflared.exe tunnel --url http://127.0.0.1:8000

pause
