# アプリケーション名

Flea-market(フリマアプリ)

## 環境構築

### Dockerビルド

1. git clone git@github.com:kokoro28k/flea-market.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build

### Laravel環境構築

1. docker compose exec php bash
2. composer install
3. cp .env.example .env 以下の環境変数を追加

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

4. アプリケーションキーの作成

```
php artisan key:generate
```

5. Stripeのキーを.envに設定
値はGithubに含まれないため、取得し設定してください

```
STRIPE_KEY=（公開鍵）
STRIPE_SECRET=（秘密鍵）
```

6. マイグレーションの実行

```
php artisan migrate
```

7. シーディングの実行

```
php artisan db:seed
```

ユーザーのパスワード: 12345678

8. storage:link の実行

```
php artisan storage:link
```

## トラブルシューティング

### VSCodeで.envを保存できない(EACCESエラー)
WSL環境でVSCodeから、.envを保存しようとすると、以下のエラーが出ることがあります。

```
EACCES: permission denied
```

対処方法(WSL内で実行)

```
sudo chown -R $USER:$USER .
```

### migrate実行時に1030エラーが出る(MYSQLのデータ破損)
migrateを実行したときに、以下のエラーが出る場合があります。

```
SQLSTATE[HY000]: General error: 1030 Got error 168 - 'Unknown (generic) error from engine'
```

対処方法
1. コンテナ停止

```
docker compose down
```

2. MYSQLのデータフォルダを削除

```
rm -rf docker/mysql/data
```

3. 再起動

```
docker compose up -d --build
```

4. 再度migrate

```
docker compose exec php bash
php artisan migrate
```

### localhostを開こうとして、strageの権限エラーが出る
Laravelがstorage/logsやstorage/framework/viewsに書き込めず、Permission deniedが発生する。
原因は、storageとbootstrap/cacheの所有者がrootのままで、www-dataが書き込めないため。

対処方法

```
cd /var/www
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```


## URL

- 開発環境: http://localhost/
- phpMyAdmin: http://localhost:8080/

## 使用技術

- PHP 8.1(FPM)
- Laravel 8.x
- MySQL 8.0.26
- Docker / docker-compose
- nginx 1.21.1

## ER図

![ER図](./src/er-flea-market.png)

## ログイン後の遷移仕様

- 初回ログイン時は users.profile_completed が false のため、プロフィール設定画面へ遷移する。
- プロフィール設定完了後に true に更新される。
- 2回目以降のログインは、トップページへ遷移する。

## テストファイル構成

### RegisterTest.php

- 会員登録機能

### LoginTest.php

- ログイン機能
- ログアウト機能

### ItemTest.php

- 商品一覧取得
- マイリスト一覧取得
- 商品検索機能
- 商品詳細情報取得

### LikeTest.php

- いいね機能

### CommentTest.php

- コメント送信機能

### PurchaseTest.php

- 商品購入機能
- 支払い方法選択機能
- 配送先変更機能

### ProfileTest.php

- ユーザー情報取得
- ユーザー情報変更

### SellTest.php

- 出品商品情報登録
