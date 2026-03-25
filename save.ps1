# Save Script for Edu SaaS
# This script commits changes locally and pushes to the CURRENT branch on GitHub.
# It DOES NOT touch the production server. Use this for daily development.

$currentBranch = git branch --show-current
if (-not $currentBranch) { $currentBranch = "dev" }

Write-Host "--- Saving changes to branch: [$currentBranch] ---" -ForegroundColor Cyan

$commitMsg = Read-Host "Enter commit message (default: 'Save work')"
if (-not $commitMsg) { $commitMsg = "Save work $(Get-Date -Format 'yyyy-MM-dd HH:mm')" }

git add .
git commit -m $commitMsg

Write-Host "--- Pushing to GitHub (main) ---" -ForegroundColor Cyan
git push origin ${currentBranch}:main

Write-Host "--- Done! Your work is saved on GitHub. ---" -ForegroundColor Green
Write-Host "Note: This did NOT deploy to the live server. Use deploy.ps1 from main branch to go live." -ForegroundColor Yellow
