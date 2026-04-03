<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 用户查询记录模型
 * @property int $id
 * @property int $user_id 查询用户ID
 * @property string $platform 平台标识
 * @property string $input_url 用户输入的原始链接
 * @property string $query_result 查询结果JSON
 * @property int $cost_score 本次扣除积分（VIP免费=0）
 * @property int $is_vip 查询时是否VIP(0否,1是)
 * @property int $status 查询状态(0失败,1成功)
 * @property string $fail_reason 失败原因
 * @property int $refunded 是否已退积分(0否,1是)
 * @property string $refund_at 退积分时间
 * @property string $create_at 查询时间
 * @class PluginMiniUserQuery
 * @package plugin\mp\model
 */
class PluginMiniUserQuery extends Model
{
    const STATUS_FAIL    = 0;
    const STATUS_SUCCESS = 1;

    public static function getPlatforms(): array
    {
        return [
            'douyin'      => ['label' => '抖音',   'class' => 'layui-bg-black'],
            'kuaishou'    => ['label' => '快手',   'class' => 'layui-bg-orange'],
            'bilibili'    => ['label' => 'B站',    'class' => 'layui-bg-cyan'],
            'weibo'       => ['label' => '微博',   'class' => 'layui-bg-red'],
            'xiaohongshu' => ['label' => '小红书', 'class' => 'layui-bg-red'],
            'other'       => ['label' => '其他',   'class' => 'layui-bg-gray'],
        ];
    }
}
