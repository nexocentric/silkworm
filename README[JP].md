(ml)Silkworm [![Build Status](https://travis-ci.org/nexocentric/silkworm.png?branch=master)](https://travis-ci.org/nexocentric/silkworm)
============
[English here.](./README.md)

(ml)Silkworm（略してSilkworm）はPHPでの開発時の**HTML**や**XML**ドキュメント**作成**を支援するマークアップライブラリです。Silkwormはアブストラクションレーヤとして、PHPをPHPのままコードを開発出来、妥当なHTMLを出力します。本ライブラリを使用する事によって、直接に`\t\t<tags>\n`を書く必要がなくなります。

Silkwormは改行、タブや空白を含めて、PHPから綺麗なHTMLコードを出力します。本ライブラリは動的に使用する事が出来、自分のプログラムのなかで、HTMLドキュメント作成を理解しやすく分解出来ます。

背景
----
Silkwormは一から作り、PhpUnitによる検証済みです。

更新履歴
--------
| バージョン | 名            | 更新内容         |
|---------|-----------------|-----------------|
| 1.00    | ao              | 初回版 |

インストールと設定
------------------------------
### インストール
Silkwormは他ライブラリへの依存がかく、下記の幾つかの方法でインストールできます：  

1. GitHubからダウンロード、インクルード方法  
  * ライブラリをGitHubからダウンロード  
  * 好きなディレクトリへ「Silkworm.php」を移動  
  * 開発するファイルに「Silkworm.php」をインクルード  

  
2. Composer経由  
  * Composerのインクルードリストに以下を追加  
  ```json
  {  
    "require": {  
      "nexocentric/silkworm": "dev-master"  
    }  
  }  
  ```

Silkwormをインスタンス化して`$html = new Silkworm();`らくらく開発開始。

#### 検証
Silkwormの全ての検証はPhpUnitで行いました。試験実体は[testsフォルダ](./tests)に入っていますので、どうぞ独自にご確認ください。

### 設定
Silkwormは使用するのに、特別な設置が必要ではありません。但し、自分の開発スタイルに合うように幾つか設定を変える事が出来ます。変更出来る設定の例が下記です。

```php
Silkworm::setSilkwormAlias("HyperTextGenerator"); //クラス名を変更

$html = new Silkworm();
$html->setIndentation("   "); //インデントを空白三個に設定する
$html->setSelfClosingTagStyle("xml"); // < />か<>
$html->setBooleanDisplayStyle("maximize"); //disabled="disabled"かdisabled
```

使い方
------
Silkwormは幾つかの使用方法があります。

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

### HTML断片保存
断片生成と保存の仕方は下記コードのように利用できます。

##### 事前準備
```php
$html = new Silkworm();
$html["error"] = $html->div(
    $html->p(
      "エラー起こしたぞう！"
    )
);

$html["falsePositive"] = $html->div(
    $html->p(
      "ごめん、ごめん、気のせいだった。"
    )
);

$html["truePositive"] = $html->div(
    $html->p(
      "ちょっと待った！"
    )
);
```

`(string)$html`でクラスを文字列化すると、全ての断片が自動的に数字そして英文字の昇順に結合されます。

##### 出力
```html
<div>
  <p>エラー起こしたぞう！</p>
</div>
<div>
  <p>ごめん、ごめん、気のせいだった。</p>
</div>
<div>
  <p>ちょっと待った！</p>
</div>
```

特定な断片を選択する事も出来ます。書式は下記です。

```php
(string)$html["falsePositive"];
```

断片に`<!DOCTYPE>`のようなヘッダー情報を追加したければ、下記の関数を利用すると便利です。

```php
(string)$html->stringWithDocumentHeader($html["truePositive"]);
```

### 高度な使い方
高度な使い方については[examplesフォルダ](./examples)を参照してください。全ての使用例はそのまま実行できますので、サーバにあげてから好きなブラウザで出力を確認出来ます。ソースコードを変えて、出力はどう変わるのかもご自由に確認してください。

連絡情報
--------
### 一般
ご連絡は下記へどうぞ:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### バグ
バグを発見された場合、どうかお知らせください。早急に対応いたします。

GitHubで問題をお知らせください。連絡とってからフォローしたいと思っております。

### 貢献
あなたの協力をお待ちしております！

プログラムに関してご協力いただけるのであれば、

1. GitHubでライブラリをForkしてください。
2. プロジェクトをより良くするための変更を加えてください。
3. 変更点のテストを行ってください。
4. Pull Request（プルリクエスト）を行ってください。
5. ドキュメント化の変更に必要な情報に関してこちらからご連絡いたします。

すべてがきちんと動作するよう、リクエストを検討いたします（*）。
*すべてのリクエストにお答えすることはできません。予めご了承ください

### フィードバック
良くても悪くて、本ライブラリを使っている皆様のフィードバックを知りたいです。ライブラリの向上に関わる意見であればなんでも聞かせてください。

謝辞
----------------
開発にあたり、私を支えてくれた、助言とアドバイスを頂いたすべての方に感謝申し上げます。

* Amy Kuwahara
* John Goodland
* Leo Lee
* Tommie Barlow
* Tom Griffin
* Wataru Kitamura

ライセンス
----------
The MIT License (MIT)

Copyright (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)

以下に定める条件に従い、本ソフトウェアおよび関連文書のファイル（以下「ソフトウェア」）の複製を取得するすべての人に対し、ソフトウェアを無制限に扱うことを無償で許可します。これには、ソフトウェアの複製を使用、複写、変更、結合、掲載、頒布、サブライセンス、および/または販売する権利、およびソフトウェアを提供する相手に同じことを許可する権利も無制限に含まれます。

上記の著作権表示および本許諾表示を、ソフトウェアのすべての複製または重要な部分に記載するものとします。

ソフトウェアは「現状のまま」で、明示であるか暗黙であるかを問わず、何らの保証もなく提供されます。ここでいう保証とは、商品性、特定の目的への適合性、および権利非侵害についての保証も含みますが、それに限定されるものではありません。 作者または著作権者は、契約行為、不法行為、またはそれ以外であろうと、ソフトウェアに起因または関連し、あるいはソフトウェアの使用またはその他の扱いによって生じる一切の請求、損害、その他の義務について何らの責任も負わないものとします。