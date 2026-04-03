<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 用户积分流水模型
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $change 积分变动量（正=增加，负=扣除）
 * @property int $balance 变动后积分余额
 * @property string $source 来源类型
 * @property int $source_id 来源关联ID（0=无关联）
 * @property string $remark 备注说明
 * @property int $admin_id 操作管理员ID（用户自触发=0）
 * @property string $create_at 记录时间
 * @class PluginMiniUserScoreLog
 * @package plugin\mp\model
 */
class PluginMiniUserScoreLog extends Model
{
    const SOURCE_ADMIN_ADJUST = 'admin_adjust';
    const SOURCE_CARD_SCORE   = 'card_score';
    const SOURCE_QUERY_COST   = 'query_cost';
    const SOURCE_QUERY_REFUND = 'query_refund';

    public static function getSources(): array
    {
        return [
            self::SOURCE_ADMIN_ADJUST => ['label' => '管理员调整', 'class' => 'layui-bg-blue'],
            self::SOURCE_CARD_SCORE   => ['label' => '卡密充值',   'class' => 'layui-bg-green'],
            self::SOURCE_QUERY_COST   => ['label' => '查询扣除',   'class' => 'layui-bg-orange'],
            self::SOURCE_QUERY_REFUND => ['label' => '查询退积分', 'class' => 'layui-bg-cyan'],
        ];
    }

    /**
     * 写入积分流水（在事务内调用）
     */
    public static function record(int $userId, int $change, int $balance, string $source, int $sourceId = 0, string $remark = '', int $adminId = 0): bool
    {
        return (bool)static::mk()->save([
            'user_id'   => $userId,
            'change'    => $change,
            'balance'   => $balance,
            'source'    => $source,
            'source_id' => $sourceId,
            'remark'    => $remark,
            'admin_id'  => $adminId,
        ]);
    }
}
