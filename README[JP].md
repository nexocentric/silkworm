(ml)Silkworm [![Build Status](https://travis-ci.org/nexocentric/silkworm.png?branch=master)](https://travis-ci.org/nexocentric/silkworm)
============
[English version here.](./README.md)
[中文说明见。](./README[ZH].md)

(ml)Silkworm（略してSilkworm）はPHPでの開発時の**HTML**や**XML**ドキュメント**作成**を支援するマークアップライブラリです。Silkwormはアブストラクションレイヤとして、PHPをPHPのままコードを開発でき、妥当なHTMLを出力します。本ライブラリを使用する事によって、直接に`\t\t<tags>\n`を書く必要がなくなります。

Silkwormは改行、タブや空白を含めて、PHPから綺麗なHTMLコードを出力します。本ライブラリは動的に使用する事ができ、自分のプログラムのなかで、HTMLドキュメント作成を理解しやすく分解できます。

背景
----
Silkwormは一から作られ、PhpUnitによる検証済みです。

更新履歴
--------
| バージョン | 名称            | 更新内容         |
|------------|-----------------|------------------|
| 1.00       | ao              | 初回版           |

インストールと設定
------------------
### インストール
Silkwormは他ライブラリへの依存がなく、下記の幾つかの方法でインストールできます:

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

Silkwormをインスタンス化して`$html = new Silkworm();`開発が始められます。

#### 検証
Silkwormの全ての検証はPhpUnitで行いました。試験実体は[testsフォルダ](./tests)に入っていますので、どうぞご確認ください。

### 設定
Silkwormは使用するのに、特別な設定は不要です。但し、自分の開発スタイルに合うように幾つか設定を変える事ができます。変更できる設定の例は以下のとおりです。

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
      "エラーが起きました！"
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

`(string)$html`でクラスを文字列化すると、全ての断片が自動的に数字、英文字の昇順に結合されます。

##### 出力
```html
<div>
  <p>エラーが起きました！</p>
</div>
<div>
  <p>ごめん、ごめん、気のせいだった。</p>
</div>
<div>
  <p>ちょっと待った！</p>
</div>
```

特定な断片を選択する事もできます。書式は以下のとおりです。

```php
(string)$html["falsePositive"];
```

断片に`<!DOCTYPE>`のようなヘッダー情報を追加したい場合、以下の関数を利用すると便利です。

```php
(string)$html->stringWithDocumentHeader($html["truePositive"]);
```

### 高度な使い方
高度な使い方については[examplesフォルダ](./examples)を参照してください。全ての使用例はそのまま実行できますので、サーバにあげてから好きなブラウザで出力を確認できます。ソースコードを変えて、出力はどう変わるのかも合わせてご確認ください。

連絡情報
--------
### 一般
ご連絡は下記へどうぞ:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### バグ
バグを発見された場合、どうかお知らせください。早急に対応いたします。

GitHubで問題をお知らせください。こちらから連絡をとった後、フォローしたいと思っております。

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
本ライブラリを使用している皆様の、ご意見、賞賛、ご批判、どのようなフィードバックも歓迎いたします。本ライブラリの向上に関わる意見をお聞かせください。
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

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.