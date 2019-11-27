# Chinese Number Converter（中文数字转换器）

__内容__:

- [简介](#简介)
- [安装](#安装)
- [用法](#用法)
- [许可证](#许可证)

简介
---
Chinese Number Converter 是中文数字转换器，基于 PHP 语言开发。目前可以将数字转换为中文表示法，未来将增加中文表示法转换为数字的功能。

安装
---

```bash
# 设置 Composer 阿里云镜像地址
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
# 安装 Chinese Number Converter
composer require imzhi/chinese-number-converter -vvv
```

用法
---

```php
use Imzhi\ChineseNumberConverter\Converter;

require_once __DIR__ . '/vendor/autoload.php';

$conv = new Converter;
$simp = $conv->simp(987654321);
$trad = $conv->trad(980654321);
$formalSimp = $conv->formalSimp(907654021);
$formalTrad = $conv->formalTrad(987054320);
echo sprintf(
    "987654321(simp): %s\n980654321(trad): %s\n907654021(formalSimp): %s\n987054320(formalTrad): %s\n",
    $simp,
    $trad,
    $formalSimp,
    $formalTrad
);
```

输出结果：

```
987654321(simp): 九亿八千七百六十五万四千三百二十一
980654321(trad): 九億八千零陸十五萬四千參百貳十一
907654021(formalSimp): 玖亿零柒佰陆拾伍万肆仟零贰拾壹
987054320(formalTrad): 玖億捌仟柒佰零伍萬肆仟參佰貳拾
```

许可证
---
Chinese Number Converter 扩展包使用 [MIT](/LICENSE) 许可证。
