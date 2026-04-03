<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 通知公告模型
 * @property int $id
 * @property string $appid 目标小程序（* 全部，多个用逗号分隔）
 * @property string $type 通知类型(system/activity/update)
 * @property string $title 通知标题
 * @property string $content 通知内容
 * @property string $jump_type 跳转类型
 * @property string $jump_value 跳转值
 * @property int $sort 排序权重
 * @property int $status 状态(0禁用,1启用)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @class PluginMiniNotice
 * @package plugin\mp\model
 */
class PluginMiniNotice extends Model
{
    /**
     * 获取通知类型列表
     */
    public static function getTypes(): array
    {
        return [
            'system'   => ['label' => lang('系统通知'), 'class' => 'layui-bg-blue'],
            'activity' => ['label' => lang('活动公告'), 'class' => 'layui-bg-orange'],
            'update'   => ['label' => lang('版本更新'), 'class' => 'layui-bg-green'],
        ];
    }

    /**
     * 获取跳转类型列表
     */
    public static function getJumpTypes(): array
    {
        return [
            ''            => ['label' => lang('仅展示（无跳转）'),           'class' => 'layui-bg-gray'],
            'navigateTo'  => ['label' => 'navigateTo ' . lang('普通跳转'),   'class' => 'layui-bg-blue'],
            'redirectTo'  => ['label' => 'redirectTo ' . lang('重定向'),     'class' => 'layui-bg-cyan'],
            'switchTab'   => ['label' => 'switchTab '  . lang('底部菜单'),   'class' => 'layui-bg-green'],
            'reLaunch'    => ['label' => 'reLaunch '   . lang('重启跳转'),   'class' => 'layui-bg-orange'],
            'webview'     => ['label' => 'webview H5'  . lang('网页'),       'class' => 'layui-bg-red'],
            'miniprogram' => ['label' => 'miniprogram '. lang('其他小程序'), 'class' => 'layui-bg-gray'],
        ];
    }
}
