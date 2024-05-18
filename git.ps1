
$content = 'GIT_REPO=https://github.com/etupdt/kgb-php.git --branch feature/security' + "`n`r"
$content += 'ENV=development'
# $content += 'ENV=production'

Set-Content C:\Temp\deploy $content

scp 'C:\Temp\deploy' 'admin@nasts2311:/share/Web/docker/Applications/kgb/'
