# Slim-template
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## 初期の設定
cp docker-compose.sample docker-compose.yml    
cp phinx.yml.sample phinx.yml   

docker-compose exec app a2enmod rewrite    
docker-compose exec app service apache2 restart    
docker-compose up -d    
docker-compose exec app ./composer.phar install    

## License
MIT
