
Remove-Item "C:\Temp\in" -recurse -Force

git clone https://github.com/etupdt/kgb-php.git 'C:\Temp\in'

Remove-Item "C:\Temp\in\.git" -Recurse -Force
Remove-Item "C:\Temp\in\.gitignore" -Recurse -Force
Remove-Item "C:\Temp\in\.vscode" -Recurse -Force

Compress-Archive -Path C:\Temp\in\* -DestinationPath C:\Temp\in\in.zip

scp admin@nasts2311 'C:\Temp\in\in.zip' '/share/Web/docker/Applications/kgb/'
