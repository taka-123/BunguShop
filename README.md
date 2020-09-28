http://118.27.17.227/bungushop/itemlist.php


## dockerの立ち上げ

cd ~/MyDocker/ec_site/laradock
docker-compose up -d apache2 mysql phpmyadmin

## 確認

* ドキュメントルート: http://localhost/bungushop/itemlist.php
* phpmyadmin: http://localhost:8888

## 課題開発環境のまとめ

* php7.2
* mysql5.7
* phpmyadmin

### ログイン情報

管理者としてログイン

* id: admin
* pass: admin

一般ユーザーとしてログイン

* id: sampleuser
* pass: password

localhost:8888
* server: testuser
* id: mysql
* pass: password

### dockerの起動・停止

~/MyDocker/ec_site/lamp_dock ディレクトリに移動し、

``` 
docker-compose up -d apache2 mysql phpmyadmin
```
でコンテナを起動します。

```
docker-compose down
```
で停止、コンテナ削除が可能です。


```
docker exec -it lamp_dock_php_1 bash
```
でコンテナ内をbashで操作できます。
