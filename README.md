TripleI.QueueObserver ![](https://travis-ci.org/triple-i/TripleI.QueueObserver.svg?branch=develop)
=======
シンプルなキュー監視サービス
---------------------------------------------
TripleI.QueueObserver は AmazonSQS を監視するシンプルな監視サービスを提供します。  
少しの設定で監視サービスを立ち上げることが出来ます。


推奨環境
------------
 * PHP 5.4+

アプリケーションの準備
---------------

### ライブラリのインストール
```
 $ composer install
```

### AWS の設定
```
export AWS_ACCESS_KEY_ID="your_aws_access_key_id"
export AWS_SECRET_ACCESS_KEY="your_secret_access_key"
export AWS_DEFAULT_REGION="your_default_region"
```


使い方
---------
使い方は簡単です。  
bin/observe に監視したキュー名称を渡してあげるだけで監視が始まります。


```
$ bin/observe YOUR_SQS_NAME
```
