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

#### Testing
All tests for Silkworm have been conducted with PhpUnit. The tests are contained in the [tests folder](./tests), so feel free to run them to make sure your version is working.

### Configuration
Silkworm doesn't require any configuration before use. However, there are a number of settings that you can use as demonstrated below.

```php
Silkworm::setSilkwormAlias("HyperTextGenerator"); //change the name of the class

$html = new Silkworm();
$html->setIndentation("   "); //indentation is now set to 3 spaces
$html->setSelfClosingTagStyle("xml"); // < /> vs <>
$html->setBooleanDisplayStyle("maximize"); //disabled="disabled" vs disabled
```

Usage
-----
There are a number of ways to use Silkworm.

### Basic
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

### Snippet Saving
You can make and save snippets as follows.

##### Setup
```php
$html = new Silkworm();
$html["error"] = $html->div(
    $html->p(
      "YOU MADE A BOO BOO!"
    )
);

$html["falsePositive"] = $html->div(
    $html->p(
      "Sorry, about that. My bad."
    )
);

$html["truePositive"] = $html->div(
    $html->p(
      "On second thought... that can't be... ;)"
    )
);
```

If you use the `(string)$html` as a string, all of the snippets will automatically be joined in numerical then alphabetical order.

##### Output
```html
<div>
  <p>YOU MADE A BOO BOO!</p>
</div>
<div>
  <p>Sorry, about that. My bad.</p>
</div>
<div>
  <p>On second thought... that can't be... ;)</p>
</div>
```

You can also choose which snippet you would like to use.

```php
(string)$html["falsePositive"];
```

### Advanced
For advanced usage please see the example folder. The examples are set up and ready for display. You should be able to access the files from your browser and see how they display there. Feel free to tinker with the examples to test out the system.

Contact
-------
### General
You can contact me via:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### Bugs
If you find any bugs while using Silkworm, I'd like to know so that I can fix them as soon as possible.

Please submit the issue via GitHub and I'll contact you for more information.

### Contributing
Your contributions are greatly appreciated!

If you would like to contribute, please:

1. Fork the library on GitHub  
2. Make any changes that you think will better the project  
3. Make tests for the changes that you've made  
4. Make a pull request  
5. I'll message you about making any needed documentation changes (so that you don't make documentation changes before you know if the pull request can be accepted or not)  

I'll go through the request to make sure that everything is okay and usable.*

[*] I would like to apologize in advance for not being able to accept all pull requests.

### Feedback
I would like to hear feedback, bad and good. Anything that promotes discussion is appreciated.

謝辞
----------------
開発にあたり、私を支えてくれた、助言とアドバイスを頂いたすべての方に感謝申し上げます。

* Amy Kuwahara
* John Goodland
* Tommie Barlow
* Wataru Kitamura

ライセンス
----------
The MIT License (MIT)

Copyright (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)

以下に定める条件に従い、本ソフトウェアおよび関連文書のファイル（以下「ソフトウェア」）の複製を取得するすべての人に対し、ソフトウェアを無制限に扱うことを無償で許可します。これには、ソフトウェアの複製を使用、複写、変更、結合、掲載、頒布、サブライセンス、および/または販売する権利、およびソフトウェアを提供する相手に同じことを許可する権利も無制限に含まれます。

上記の著作権表示および本許諾表示を、ソフトウェアのすべての複製または重要な部分に記載するものとします。

ソフトウェアは「現状のまま」で、明示であるか暗黙であるかを問わず、何らの保証もなく提供されます。ここでいう保証とは、商品性、特定の目的への適合性、および権利非侵害についての保証も含みますが、それに限定されるものではありません。 作者または著作権者は、契約行為、不法行為、またはそれ以外であろうと、ソフトウェアに起因または関連し、あるいはソフトウェアの使用またはその他の扱いによって生じる一切の請求、損害、その他の義務について何らの責任も負わないものとします。
