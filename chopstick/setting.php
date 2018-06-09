<?php
return array
(
    'mode'              => 'development',
    'application_key'   => 'your_application_key',
    'chopstick_dir'     => __DIR__.'/',
    'public_dir'        => __DIR__.'/../public/',
    'time_zone'         => 'Tokyo/Asia',
);
/*
フレームワーク全体の挙動に影響を与える設定が可能です。

## mode

モードを設定します。モードを切り替えることで、データベースの接続先などが変わります。

|development|開発|
|staging|ステージング|
|production|製品・本番|

## application_key

アプリケーション固有の文字列を指定します。セッション情報などが競合しないようにするために利用します。

## chopstick_dir

フレームワークそのものが入っているディレクトリを指定します。

## public_dir

index.php が入っているフォルダを指定します。

*/