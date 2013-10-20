(ml)Silkworm [![Build Status](https://travis-ci.org/nexocentric/silkworm.png?branch=master)](https://travis-ci.org/nexocentric/silkworm)
============
[English version here.](./README.md)  
[日本語はこちらです。](./README[JP].md)

(ml)Silkworm，（绰号Silkworm）是帮**遍****HTML**和**XML**文的**PHP*代码程序库。
Silkworm是从HTML的抽象层，所以遍程序的时候可以只用PHP开发。不要自己的输出HTML`\t\t<tags>\n`。

Silkworm是从遍了的PHP文件输出回车和好好排的HTML和XML。这程序库有很都的用法，可以分解遍HTML程合适你的开发方法。

历史
-------
Silkworm从头开始编写了。用了PhpUnit测试了。

Changelog
---------
| 版本 | 名字            | 修改内容         |
|---------|-----------------|-----------------|
| 1.00    | ao              | 第一版 |

装机和设置
------------------------------
### 安装
Silkworm没有依赖和别序库。用下个方法可以安装：

1. 从GitHub复制导入法  
  * 从GitHub复制程序库  
  * 搬Silkworm.php到选文件夹  
  * 想开发文件里打包Silkworm.php  

2. 经由Composer  
  * composer包加下个代码
  ```json
  {  
    "require": {  
      "nexocentric/silkworm": "dev-master"  
    }  
  }  
  ```

这样得实例以后`$html = new Silkworm();`可以开始开发.

#### 测试
Silkworm的全部的测试用PhpUnit完了。测试在[tests文件夹](./tests)里，想确定这版本的Silkworm好不好的话，请测试一下。

### Configuration
用Silkworm前不要变设置。但是，Silkworm有多可以变的设置。例子在下面。

```php
Silkworm::setSilkwormAlias("HyperTextGenerator"); //变类的名字

$html = new Silkworm();
$html->setIndentation("   "); //用隔开变排法
$html->setSelfClosingTagStyle("xml"); // < />还有<>
$html->setBooleanDisplayStyle("maximize"); //disabled="disabled"还有disabled
```

使用说明
-----
Silkworm有很多的用法。

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
想保存HTML断片的话，下面和一样得保。

##### 事前准备
```php
$html = new Silkworm();
$html["error"] = $html->div(
    $html->p(
      "你做了错呀!"
    )
);

$html["falsePositive"] = $html->div(
    $html->p(
      "对不起。没事。"
    )
);

$html["truePositive"] = $html->div(
    $html->p(
      "请等...我想了没错呀..."
    )
);
```

断片保存以后，这样地`(string)$html`文字列化Silkworm对象的话，全部的断片自动地连接。连接顺序是数字上顺，以后英语字母表上顺。

##### 输出
```html
<div>
  <p>你做了错呀!</p>
</div>
<div>
  <p>对不起。没事。</p>
</div>
<div>
  <p>请等...我想了没错呀...</p>
</div>
```

也可以选用的断片。

```php
(string)$html["falsePositive"];
```

想引用`<!DOCTYPE>`和一样的头文件的话，想下面和一样的可以输出。

```php
(string)$html->stringWithDocumentHeader($html["truePositive"]);
```

### 先进
先进的用法请看[examples文件夹](./examples)。例子已经准备了。从你的浏览器可以看看。请变代码学习这程序库的用法一下。

联系信息
-------
### 一般
用下个联系信息可以连我:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### 出错
用Silkworm的时候发现出错的话，请告诉我一下。那事我快快地修改。

请用GitHub提Issue还有那出错的内容给我，以后我连你商量商量。

### 貢献
你可以貢献我很高兴。

想貢献的话请:

1. 用GitHub复制这程序库  
2. 修改代码一下  
3. 遍测试为修改的部分  
4. 申请pull request  
5. 以后，我连您请您做说明一下  

我确定您的pull request看看可以用不可以用您的代码。*

[*] 我事前谢罪不能有全部的pull requests的是。

### 反馈
有反馈的话请给我，想看看。好不好没关系。好得修改这程序库的是多可以。

感谢
----------------
我想谢帮我开发这程序库的大家。谢谢你们给我很好的帮助。

* Amy Kuwahara
* John Goodland
* Leo Lee
* Tommie Barlow
* Tom Griffin
* Wataru Kitamura

版权
---------
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