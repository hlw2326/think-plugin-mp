<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 卡密使用记录模型
 * @property int $id
 * @property int $card_type 卡密类型(1Score,2会员)
 * @property int $card_id 卡密ID
 * @property string $card_code 卡密码
 * @property int $user_id 使用用户ID
 * @property int $use_time 使用时间戳
 * @property string $use_data 使用数据(JSON格式)
 * @property string $ip_address 使用IP地址
 * @property string $create_at 创建时间
 * @class PluginMiniCardLog
 * @package plugin\mp\model
 */
class PluginMiniCardLog extends Model
{
    const TYPE_SCORE  = 1;
    const TYPE_VIP    = 2;
}
