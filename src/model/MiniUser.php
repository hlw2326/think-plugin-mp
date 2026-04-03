<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 小程序用户模型
 * @property string $id 用户ID（租户级唯一）
 * @property string $openid 微信OpenID
 * @property string $pid 推荐人用户ID
 * @property string $unionid 微信UnionID
 * @property string $nickname 用户昵称
 * @property string $avatar_url 用户头像
 * @property int $gender 性别(0未知,1男,2女)
 * @property string $phone 手机号
 * @property int $score 积分
 * @property int $vip_time 会员过期时间戳(0未开通)
 * @property int $status 状态(1正常,0禁用)
 * @property int $deleted 删除状态(1已删除,0正常)
 * @property string $login_ip 本次登录IP
 * @property string $last_login_ip 上次登录IP
 * @property string $login_at 本次登录时间
 * @property string $last_login_at 上次登录时间
 * @property string $remark 备注
 * @property string $device_model 手机型号
 * @property string $device_system 操作系统版本
 * @property int    $screen_width  屏幕宽度(px)
 * @property int    $screen_height 屏幕高度(px)
 * @property string $sdk_version   微信基础库版本
 * @property string $app_version   小程序版本号
 * @property string $app_channel   小程序来源渠道
 * @property string $extra        扩展字段(JSON)
 * @property string $create_at 注册时间
 * @property string $update_at 更新时间
 * @class PluginMiniUser
 * @package plugin\mp\model
 */
class PluginMiniUser extends Model
{
}
