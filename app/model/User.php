<?php
/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/11 13:17
 * Description
 */

namespace App\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use support\Cache;

class User extends Model
{
    protected $guarded = [];

    const EXPIRE_AT = 60*60*24*7;

    protected static function boot()
    {
        parent::boot();
    }

    public function password(): Attribute
    {
        return new Attribute(
            set: fn($value) => strlen($value) === 60 ? $value : password_hash($value, PASSWORD_DEFAULT)
        );
    }

    /**
     * Author 王小大 [m@wangxiaoda.com]
     * Date 2022/8/12 13:07
     * @return string
     */
    public function authKey(): string
    {
        $salt = cache_remember("salt_{$this->id}", static::EXPIRE_AT, function () {
            return md5(Str::random(32).time());
        });

        return md5($this->id.$this->password.$salt);
    }

    /**
     * 密码烟杂
     * Author 王小大 [m@wangxiaoda.com]
     * Date 2022/8/12 14:57
     * @param  string  $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Author 王小大 [m@wangxiaoda.com]
     * DateTime 2022/8/11 22:17
     * @param  int  $uid
     * @param  string  $identity
     * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public static function checkIdentityByUid($uid, $identity)
    {
        if (empty($uid)) {
            return false;
        }
        $user = User::query()->find((int) $uid);
        if (!$user) {
            return false;
        }

        return $user->authKey() === $identity ? $user : false;
    }
}