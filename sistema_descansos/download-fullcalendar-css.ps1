$dest = 'public/vendor/fullcalendar'
if (-not (Test-Path $dest)) { New-Item -ItemType Directory -Path $dest | Out-Null }
$url = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css'
$destPath = Join-Path $dest 'main.min.css'
if (-not (Test-Path $destPath)) { Invoke-WebRequest -Uri $url -OutFile $destPath }
