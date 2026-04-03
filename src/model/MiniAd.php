<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 广告模型
 * @property int $id
 * @property string $appid 所属小程序AppID
 * @property string $name 广告名称
 * @property string $ad_type 广告类型
 * @property string $unit_id 广告单元ID
 * @property string $position 广告位置
 * @property int $sort 排序权重
 * @property int $status 状态(0禁用,1启用)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @class PluginMiniAd
 * @package plugin\mp\model
 */
class PluginMiniAd extends Model
{
    /**
     * 获取广告类型列表
     */
    public static function getAdTypes(): array
    {
        return [
            'banner'         => ['label' => 'Banner ' . lang('横幅广告'),   'class' => 'layui-bg-blue'],
            'interstitial'   => ['label' => lang('插屏广告'),               'class' => 'layui-bg-orange'],
            'video'          => ['label' => lang('激励视频广告'),           'class' => 'layui-bg-green'],
            'native'         => ['label' => lang('原生模板广告'),           'class' => 'layui-bg-cyan'],
            'grid'           => ['label' => lang('格子广告'),               'class' => 'layui-bg-red'],
        ];
    }
}
