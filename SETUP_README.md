
# プロジェクトのセットアップ手順

このドキュメントでは、プロジェクトのセットアップに必要な手順を説明します。このプロジェクトは Laravel Sail を使用して開発されています。

## 前提条件

- Docker Desktop がインストールされていることを確認してください。
- Git がインストールされていること。

## 初期設定

以下の手順に従って、プロジェクトをローカル環境で稼働させてください。

### 1. リポジトリのクローン

Git を使用してプロジェクトをローカルにクローンします。

```bash
git clone https://example.com/your-project.git
cd your-project
```

### 2. 環境設定ファイルのコピー

`.env.example` ファイルを `.env` としてコピーし、適切に設定を行います。

```bash
cp .env.example .env
```

### 3. Dockerコンテナのビルド

最初のセットアップ時、または `docker-compose.yml` が更新された場合に Docker コンテナをビルドします。

```bash
composer install
npm install
yaml install
./vendor/bin/sail build
# sudo apt-get install docker-ce docker-ce-cli containerd.io
```

### 4. Laravel Sailの起動

Laravel Sail とその依存サービスのコンテナを起動します。

```bash
./vendor/bin/sail up -d
```

### 5. 依存関係のインストール

Composer を使用して、必要な PHP 依存関係をインストールします。

```bash
./vendor/bin/sail composer install
```

### 6. アプリケーションキーの生成

Laravel のアプリケーションキーを生成します。

```bash
./vendor/bin/sail artisan key:generate
```

### 7. データベースマイグレーション

データベースのテーブルを作成します。

```bash
./vendor/bin/sail artisan migrate
```

## サーバへのアクセス

すべての設定が完了したら、ブラウザを開いて `http://localhost` にアクセスし、アプリケーションが正しく動作していることを確認してください。

## コンテナの停止方法

開発を一時停止する場合は、以下のコマンドを使用してコンテナを停止します。

```bash
./vendor/bin/sail down
```
