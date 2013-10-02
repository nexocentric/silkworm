Silkworm
===================
[English here.](./README)

Silkwormは、PHPでHTMLドキュメントを作りやすくするためのPHPライブラリーです。あなたがPHPでプログラミングを組んでいて、後でHTML presentationをしなければならないときでも、心配はいりません。SilkwormはHTMLからの抽象化レイヤーなので、あなたはPHPでのプログラミングに集中することができます。

SilkwormはあなたのPHPファイルからキャリッジ・リターンとインデントを作成します。ライブラリはダイナミックリンクを有していて、モジュラー形式での使用が可能です。プログラミング中、あなたのドキュメントの作成状況に応じてライブラリを分けることができます。

背景
----
Silkwormはゼロから作成され、PhpUnitによるテスト済みです。

更新履歴
--------
| バージョン     |     名バージョン |  更新内容 |
|------------|--------------|----------|
| 1.00       | ao           | 初期     |

設定とインストール
------------------
### インストール
Silkwormは他ライブラリへの依存がありません。以下の方法でインストールが可能です。
1. GitHubからインクルード
  * GitHubからライブラリをコピー
  * Silkworm.phpを任意のディレクトリへ移動
  * Silkworm.phpを開発するファイルへインクルード
2. Composer経由
  * Add the following to your composer requirements
  ```json
  {
    "require": {
      "nexocentric/silkworm": "1.*"
    }
  }
  ```
インスタンス化し`$html = new Silkworm();`開発スタート。

#### テスト
Silkworm用のテストはすべてPhpUnitを使用して実施されています。テストは[test folder](./tests)内に入っています。実行し、バージョンが正常に動作していることをご確認ください。

### 設定
Silkwormは使用前の設定は不要です。現在、インデント作成のためのフォントの変更のみが可能です。タブキーとスペースで変更ができます。

```php
$html = new Silkworm();
$html->setIndentation("   "); //indentation is now set to 3 spaces
```

インストール方法
----------------
Silkwormは使用方法が幾つかあります。

### 基本
```php
$table = array(
    array("Version", "Name", "Changes"),
    array("1.00", "ao", "initial release")
);

$html = new Silkworm();
$html->html(
    $html->head(
        $html->title("A title")
    ),
    $html->body(
        $html->p("class", "main-text", "This is Silkworm version 1.00!"),
        $html->newline(),
        $html->comment("information about silkworm"),
        $html->autoTable($table, true)
    )
);
```

### 高度
高度の使用方法については、使用例フォルダをご確認ください。使用例が見られるようになっています。ご使用のブラウザからアクセスして、どのように表示されているかをご確認ください。使用例を変えて、システムのテストをしてみてください。

連絡情報
--------
### 全般
ご連絡は以下で↓:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### バグ報告
バグを発見された場合、どうぞお知らせください。出来るだけ早急に対応いたします。

GitHubで問題をお知らせください。問題を確認後、詳細をお伝えいたします。

### 貢献について
コード向上のお手伝いは大歓迎です。

プログラムに関してご協力いただけるのであれば:
1. GitHubでライブラリをForkしてください。
2. プロジェクトをより良くするための変更を加えてください。
3. 変更点のテストを行ってください。
4. Pull Request（プルリクエスト）を行ってください。
5. ドキュメント化の変更に必要な情報に関してこちらからご連絡いたします(so that you don't make documentation changes before you know if the pull request can be accepted or not)

すべてがきちんと動作するよう、リクエストを検討いたします。※

※すべてのリクエストにお答えすることはできません。予めご了承ください。

謝辞
----------------
開発にあたり、私を支えてくれた、助言とアドバイスを頂いたすべての方に感謝申し上げます。

* Brandon Kuwahara
* Tommie Barlow
* Wataru Kitamura

ライセンス
----------
The MIT License (MIT)

Copyright (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)

以下に定める条件に従い、本ソフトウェアおよび関連文書のファイル（以下「ソフトウェア」）の複製を取得するすべての人に対し、ソフトウェアを無制限に扱うことを無償で許可します。これには、ソフトウェアの複製を使用、複写、変更、結合、掲載、頒布、サブライセンス、および/または販売する権利、およびソフトウェアを提供する相手に同じことを許可する権利も無制限に含まれます。

上記の著作権表示および本許諾表示を、ソフトウェアのすべての複製または重要な部分に記載するものとします。

ソフトウェアは「現状のまま」で、明示であるか暗黙であるかを問わず、何らの保証もなく提供されます。ここでいう保証とは、商品性、特定の目的への適合性、および権利非侵害についての保証も含みますが、それに限定されるものではありません。 作者または著作権者は、契約行為、不法行為、またはそれ以外であろうと、ソフトウェアに起因または関連し、あるいはソフトウェアの使用またはその他の扱いによって生じる一切の請求、損害、その他の義務について何らの責任も負わないものとします