(ml)Silkworm [![Build Status](https://travis-ci.org/nexocentric/silkworm.png?branch=master)](https://travis-ci.org/nexocentric/silkworm)
============
[English version here.](./README.md)  
[日本語はこちらです。](./README[JP].md)

(ml)Silkworm，（绰号Silkworm）是帮助**编辑HTML**和**XML**文的**PHP**的代码程序库。
Silkworm是从HTML的抽象层，所以编程序的时候可以只用PHP开发。不要自己的输出HTML`\t\t<tags>\n`。

Silkworm输出的HTML和XML比只用PHP更简单清楚。这程序库有很多的用法，可以分解HTML然后合成您想用想要的程序。

历史
-------
Silkworm是我从零开始编写完成的程序。而且用PhpUnit进行测试。

Changelog
---------
| 版本 | 名字            | 修改内容         |
|---------|-----------------|-------------------------------------------|
| 1.00    | ao              | 第一版                           |
| 1.01    | beige           | 代码显示修改                     |
| 1.02    | cyan            | 更新英语说明                    |
| 1.03    | daidai          | 开始写第一版日语说明                   |
| 1.04    | emerald         | 写完日语说明                 |
| 1.05    | fen             | 开始写第一版中文说明                    |
| 1.06    | gin             | 修改文字显示错误，加新测试    |
| 1.07    | hui             | 写完中文说明，删除临时文件    |

装机和设置
------------------------------
### 安装
Silkworm不依赖别的程序库。用下列方法可以安装：

1. 从GitHub复制导入法  
  * 从GitHub复制程序库  
  * 移动Silkworm.php到选定文件夹  
  * 在想开发文件里应用Silkworm.php  

2. 经由Composer  
  * composer应用下列代码
  ```json
  {  
    "require": {  
      "nexocentric/silkworm": "dev-master"  
    }  
  }  
  ```

然后编写下列代码`$html = new Silkworm();`可以开始开发.

#### 测试
Silkworm全部用PhpUnit测试完了。测试在[tests文件夹](./tests)里，如果您想确定这版本的Silkworm好不好，请测试一下。

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

### HTML部分保存
想保存HTML部分的话，请叁考和下列一样保存方法。

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

部分保存以后，把像`(string)$html`的结合后，所有部分的数字英文字母将按顺序排列。

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

也可以选用这样的部分。

```php
(string)$html["falsePositive"];
```

想引用`<!DOCTYPE>`背景标题的话，请叁考下列的输出方法。

```php
(string)$html->stringWithDocumentHeader($html["truePositive"]);
```

### 高级用法
学习高级用法时，请叁考[examples文件夹](./examples)。已经准备了叁考资料。您可以通过您的浏览器查看。请改尝试写代码学习这程序库的用法一下。

联系信息
-------
### 一般
请用下列联系方式联系我:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### 出错的话
用Silkworm的时候发现错误的话，请告诉我。我会尽快修改。

请用GitHub写Issue给我，以后我会及时回复。

### 如果您想帮助我
感谢您对我的帮助。

愿意帮助我的话:

1. 用GitHub复制这程序库  
2. 修改代码  
3. 编测试为修改的部分  
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