<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 用户登录Token模型
 * @property int    $id 主键ID
 * @property string $user_id 用户ID
 * @property string $token 登录Token
 * @property string $device_model 手机型号
 * @property string $device_system 操作系统版本
 * @property int    $screen_width 屏幕宽度(px)
 * @property int    $screen_height 屏幕高度(px)
 * @property string $sdk_version 微信基础库版本
 * @property string $app_version 小程序版本号
 * @property string $app_channel 小程序来源渠道
 * @property string $client_ip 登录IP
 * @property string $login_at 登录时间
 * @property int    $expire_time Token过期时间戳(0=不过期)
 * @property int    $status 状态(1有效,0失效)
 * @property string $update_at 更新时间
 * @class PluginMiniUserToken
 * @package plugin\mp\model
 */
class PluginMiniUserToken extends Model
{
    /** Token 状态常量 */
    const STATUS_ACTIVE  = 1; // 有效
    const STATUS_EXPIRED = 0; // 失效

    /**
     * 根据 Token 查询记录
     */
    public static function findByToken(string $token): ?array
    {
        $item = static::mk()->where(['token' => $token, 'status' => 1])->find();
        if (empty($item)) {
            return null;
        }
        // 过期检查
        if ($item['expire_time'] > 0 && $item['expire_time'] < time()) {
            static::mk()->where('id', $item['id'])->update(['status' => 0]);
            return null;
        }
        return $item->toArray();
    }

    /**
     * 根据 Token 删除记录（退出登录）
     */
    public static function removeByToken(string $token): bool
    {
        return static::mk()->where('token', $token)->update(['status' => 0]) !== false;
    }

    /**
     * 根据用户ID列出所有有效登录设备
     */
    public static function listActiveDevices(string $userId): array
    {
        return static::mk()
            ->where(['user_id' => $userId, 'status' => 1])
            ->where(function ($q) {
                $q->where('expire_time', 0)->whereOr('expire_time', '>', time());
            })
            ->order('login_at', 'desc')
            ->select()
            ->toArray();
    }

    /**
     * 生成登录Token
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * 写入登录记录
     */
    public static function recordLogin(
        string $userId,
        string $token,
        array  $device = [],
        string $ip = '',
        int    $expireTime = 0
    ): int {
        return static::mk()->insertGetId([
            'user_id'       => $userId,
            'token'         => $token,
            'device_model'  => $device['device_model']  ?? '',
            'device_system' => $device['device_system'] ?? '',
            'screen_width'  => intval($device['screen_width']  ?? 0),
            'screen_height' => intval($device['screen_height'] ?? 0),
            'sdk_version'   => $device['sdk_version']   ?? '',
            'app_version'   => $device['app_version']   ?? '',
            'app_channel'   => $device['app_channel']   ?? '',
            'client_ip'     => $ip,
            'expire_time'   => $expireTime,
            'status'        => 1,
        ]);
    }
}
