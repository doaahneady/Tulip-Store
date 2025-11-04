$RepoPath = "D:\Tulip-Store"
$Branch = "main"

while ($true) {
    Set-Location $RepoPath
    Write-Host "Checking for updates at $(Get-Date)"
    git fetch origin $Branch

    $local = git rev-parse $Branch
    $remote = git rev-parse origin/$Branch

    if ($local -ne $remote) {
        Write-Host "Updates found, pulling..."
        git pull origin $Branch
    } else {
        Write-Host "No updates found."
    }

    Start-Sleep -Seconds 30
}
