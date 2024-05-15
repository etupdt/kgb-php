
Remove-Item "N:\docker\Applications\ecf-garage-back\in" -recurse -Force

git clone https://github.com/etupdt/ecf-garage-back.git 'N:\docker\Applications\ecf-garage-back\in'

Remove-Item "N:\docker\Applications\ecf-garage-back\in\.git" -Recurse -Force

ssh admin@nasts2311 -f '/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker compose -f /share/Web/docker/Applications/ecf-garage-back/ecf-garage-back.yml up --force-recreate --build -d 2> /tmp/log.log'
