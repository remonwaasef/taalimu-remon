# Helper script to set up SSH Keys for password-less deployment
# Run this once on your Windows machine.

$ip = "46.202.155.30"
$user = "root"

Write-Host "--- 1. Checking for existing SSH Key ---" -ForegroundColor Cyan
$keyPath = "$HOME\.ssh\id_rsa.pub"

if (-not (Test-Path $keyPath)) {
    Write-Host "No SSH Key found. Generating a new one..." -ForegroundColor Yellow
    ssh-keygen -t rsa -b 4096 -f "$HOME\.ssh\id_rsa" -N '""'
} else {
    Write-Host "Found existing SSH key at $keyPath" -ForegroundColor Green
}

Write-Host "--- 2. Uploading Public Key to Server ---" -ForegroundColor Cyan
Write-Host "You will be asked for the ROOT PASSWORD one last time." -ForegroundColor Yellow

$pubKey = Get-Content $keyPath
$remoteCmd = "mkdir -p ~/.ssh && echo '$pubKey' >> ~/.ssh/authorized_keys && chmod 700 ~/.ssh && chmod 600 ~/.ssh/authorized_keys"

ssh ${user}@${ip} $remoteCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "--- DONE! ---" -ForegroundColor Green
    Write-Host "You can now run .\deploy.ps1 without being asked for a password." -ForegroundColor White
} else {
    Write-Host "--- FAILED to upload key. Please check your connection. ---" -ForegroundColor Red
}
