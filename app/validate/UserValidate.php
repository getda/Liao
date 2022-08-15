<?php
namespace App\validate;

use think\Validate;

/**
 * Author 王世杰 [m@wangxiaoda.com]
 * Date 2022/8/12 13:10
 * Description
 */
class UserValidate extends Validate
{
    protected $rule = [
        'nickname' => 'require|chsAlphaNum|max:18',
        'password' => 'require|alphaNum|min:6|max:10',
    ];

    protected $message = [
        'name.require'      => '请输入名称',
        'name.chsAlphaNum'  => '名称只能是汉字、数字、字母组合',
        'name.max'          => '名称最大18位',
        'password.require'  => '请输入口令',
        'password.alphaNum' => '口令只能是数字、字母组合',
        'password.min'      => '口令最低6位',
        'password.max'      => '口令最大10位',
    ];
}