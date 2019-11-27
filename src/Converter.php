<?php

namespace Imzhi\ChineseNumberConverter;

use Imzhi\ChineseNumberConverter\Exceptions\InvalidArgumentException;

/**
 * Class Converter.
 *
 * @author imzhi <yxz_blue@126.com>
 */
class Converter
{
    /**
     * 位数（十位、百位等）中文对应关系
     *
     * @var array
     */
    protected $placeRels = [
        10 => '十',
        100 => '百',
        1000 => '千',
        10000 => '万',
        100000000 => '亿',
        1000000000000 => '兆',
        10000000000000000 => '京',
    ];

    /**
     * 数字（0-9）中文对应关系
     *
     * @var array
     */
    protected $digitRels = [
        0 => '零',
        1 => '一',
        2 => '二',
        3 => '三',
        4 => '四',
        5 => '五',
        6 => '六',
        7 => '七',
        8 => '八',
        9 => '九',
    ];

    /**
     * 数字（0-9）中文对应关系
     *
     * @var array
     */
    protected $formalSimpRels = [
        '一' => '壹',
        '二' => '贰',
        '三' => '叁',
        '四' => '肆',
        '五' => '伍',
        '六' => '陆',
        '七' => '柒',
        '八' => '捌',
        '九' => '玖',
        '十' => '拾',
        '百' => '佰',
        '千' => '仟',
    ];

    /**
     * 中文简繁体对应关系
     *
     * @var array
     */
    protected $tradRels = [
        '二' => '貳',
        '三' => '參',
        '六' => '陸',
        '负' => '負',
        '点' => '點',
        '万' => '萬',
        '亿' => '億',
    ];

    /**
     * 转换成中文简体数字
     *
     * @param  mixed $input 输入字符串
     * @return string
     *
     * @throws \Imzhi\ChineseNumberConvertor\Exceptions\InvalidArgumentException
     */
    public function simp($input)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException(sprintf('%s is not a number', $input));
        }

        return $this->reckonOutput($input);
    }

    /**
     * 转换成中文繁体数字
     *
     * @param  mixed $input 输入字符串
     * @return string
     *
     * @throws \Imzhi\ChineseNumberConvertor\Exceptions\InvalidArgumentException
     */
    public function trad($input)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException(sprintf('%s is not a number', $input));
        }

        return str_replace(array_keys($this->tradRels), array_values($this->tradRels), $this->reckonOutput($input));
    }

    /**
     * 转换成传统的中文简体数字
     *
     * @param  mixed $input 输入字符串
     * @return string
     *
     * @throws \Imzhi\ChineseNumberConvertor\Exceptions\InvalidArgumentException
     */
    public function formalSimp($input)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException(sprintf('%s is not a number', $input));
        }

        return str_replace(array_keys($this->formalSimpRels), array_values($this->formalSimpRels), $this->reckonOutput($input));
    }

    /**
     * 转换成传统的中文繁体数字
     *
     * @param  mixed $input 输入字符串
     * @return string
     *
     * @throws \Imzhi\ChineseNumberConvertor\Exceptions\InvalidArgumentException
     */
    public function formalTrad($input)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException(sprintf('%s is not a number', $input));
        }

        $rels = array_merge($this->formalSimpRels, $this->tradRels);

        return str_replace(array_keys($rels), array_values($rels), $this->reckonOutput($input));
    }


    /**
     * 计算整数部分中文表示法
     *
     * @param  mixed   $integer 要计算的整数
     * @param  integer $bit     接近整数的位数
     * @return string           返回中文表示法
     */
    protected function reckonInteger($integer, $bit = 0)
    {
        if (!$integer) {
            return '';
        }

        $zero = $bit / $integer > 10 ? '零' : '';
        if ($integer < 10) {
            return $zero . $this->digitRels[$integer];
        }

        $result = '';
        $reverse = array_reverse($this->placeRels, true);
        foreach ($reverse as $num => $txt) {
            $int = intval($integer / $num);
            if (!$int) {
                continue;
            }

            $result = $zero . $this->reckonInteger($int);
            $result .= $txt;
            $result .= $this->reckonInteger($integer % $num, $num);
            break;
        }

        return $result;
    }

    /**
     * 计算小数部分中文表示法
     *
     * @param  string  $decimal 要计算的小数
     * @return string           返回中文表示法
     */
    protected function reckonDecimal($decimal)
    {
        if (!$decimal) {
            return '';
        }

        $arr = explode('.', strval($decimal));
        $str = end($arr);

        $result = '点';
        foreach (str_split($str) as $digit) {
            $result .= $this->digitRels[$digit];
        }

        return $result;
    }

    /**
     * 从输入计算输出结果
     *
     * @param  mixed $input  输入字符串
     * @return string        返回中文表示法
     */
    protected function reckonOutput($input)
    {
        // 去掉首尾多余的 0
        $input = preg_replace(['/^(\-?)0+/', '/(\.\d*?)0+$/'], ['$1', '$1'], strval($input));

        $array = explode('.', strval($input));

        $prefix = $array[0] < 0 ? '负' : (!abs($array[0]) ? '零' : '');
        $integer = $this->reckonInteger(abs($array[0]));
        $decimal = $this->reckonDecimal($array[1] ?? '');
        $output = $prefix . $integer . $decimal;

        return $output;
    }
}
