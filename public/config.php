<?php

//error_reporting(-1);
error_reporting(0);

// 直接呼び出された場合は終了
if (count(get_included_files())==1) {
  exit;
}

// 環境変数からMySQL接続情報を取得
$services = json_decode($_ENV['VCAP_SERVICES'], true);
$service = $services['p-mysql'][0];  // pick the first MySQL service

// DBの接続情報
define('DB_HOST',     $service['credentials']['hostname'] . ':' . $service['credentials']['port']);          // dbのホスト名
define('DB_USER',     $service['credentials']['username']);     // dbのユーザー名
define('DB_PASSWORD', $service['credentials']['password']);      // dbのパスワード
define('DB_DATABASE', $service['credentials']['name']); // dbのデータベース名
define('DB_CHARSET',  'UTF8MB4');            // MySQL 5.5未満の場合はUTF8を指定してください

// サーバーの設定情報
$application = json_decode($_ENV['VCAP_APPLICATION'], true);
$domain = $application['application_uris'][0];
define('DOMAIN',        $domain);           // ドメイン名
define('PASSWORD_SALT', '1234567890qwertyuiop'); // 適当な英数字を入力してください

// 設定クラス読み込み
define('WWW_DIR', dirname(__FILE__) . '/');
require(dirname(__FILE__) . '/../app/core/bootstrap.php');
