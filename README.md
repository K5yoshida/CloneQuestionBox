# CloneQuestionBox
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## このリポジトリについて
このリポジトリは昨年12月に流行した質問箱というサービスに類似したサービスを
簡単に構築できるようにするために、公開されたオープンソースプロジェクトです。     
     
MITライセンスの範囲内で自由にこのリポジトリをフォークしてサービスをデプロイすることができます。     
このプログラムはプログラミングの理解をする必要はなく、WordPressのように簡単にデプロイできるのが特徴です。     
     
Dockerを利用しているため、初期の環境構築もDockerが動く環境なら必要ありません。     
また、問題のある部分を発見した場合には気軽にissueもしくはプルリクをしてください。      

## リポジトリのクローン
```
git clone git@github.com:syoou/CloneQuestionBox.git    
    
                          or
    
git clone https://github.com/syoou/CloneQuestionBox.git
```

## 環境変数の設定

cp docker-compose.sample docker-compose.yml    
cp share/phinx.yml.sample share/phinx.yml      
cp share/.env.sample share/.env     

プログラムの改変は必要ありませんが、環境変数の設定をする必要があります。     
上記のコマンドを実行後、shareファイルの中の.envを編集します。
     
以下、環境変数の説明です。

CONSUMER_KEY       *ツイッターAPPのキー     
CONSUMER_SECRET    *ツイッターAPPのシークレット     
APP_URL            *本アプリを利用するマシーンのURL     
CALLBACK           *本アプリを利用するマシーンのURLに/auth/twitter/callbackを追加したもの
APP_DEBUG          *デバッグモードか本番環境かを設定する開発中以外はfalseにする
APP_NAME           *アプリケーションの名前(この名前が紐付いている部分は全てこの名前になります)

## 初期の設定(Docker)
docker-compose build     
docker-compose up -d    
docker-compose exec app a2enmod rewrite    
docker-compose exec app service apache2 restart    
docker-compose up -d    
docker-compose exec app ./composer.phar install    
docker-compose exec app bash
./vendor/bin/phinx migrate


## もしエラーフが出た場合
docker logs -f [コンテナID]

## License
MIT
