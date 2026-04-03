<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 积分充值卡密模型
 * @property int $id 主键ID
 * @property string $batch_no 批次号
 * @property int $admin_id 创建管理员ID
 * @property string $card_code 卡密码
 * @property int $score 积分数量
 * @property int $max_users 最大使用用户数(0无限制)
 * @property int $user_limit 每用户使用次数限制
 * @property int $expire_time 卡密到期时间戳(0永不过期)
 * @property string $remark 备注
 * @property int $status 状态(0禁用,1启用)
 * @property int $sort 排序权重
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $used_count 已使用用户数（虚拟）
 * @class PluginMiniCardScore
 * @package plugin\mp\model
 */
class PluginMiniCardScore extends Model
{
    protected $append = ['used_count'];

    public function getUsedCountAttr($value, $data): int
    {
        if (empty($data['card_code'])) return 0;
        return PluginMiniCardLog::mk()
            ->where(['card_code' => $data['card_code'], 'card_type' => PluginMiniCardLog::TYPE_SCORE])
            ->group('user_id')
            ->count();
    }
}
