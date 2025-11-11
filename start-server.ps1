#!/usr/bin/env pwsh
# Start SIPEKAN Server
Set-Location $PSScriptRoot
Write-Host "🚀 Starting SIPEKAN Server..." -ForegroundColor Green
Write-Host "📍 Server akan berjalan di: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "📱 Frontend dan Backend terintegrasi dalam satu localhost" -ForegroundColor Cyan
Write-Host ""
php artisan serve
