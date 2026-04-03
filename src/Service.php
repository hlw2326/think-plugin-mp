<?php

declare(strict_types=1);

namespace plugin\mp;

use think\admin\Plugin;

/**
 * 小程序插件服务注册
 * @class Service
 * @package plugin\mp
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '小程序插件';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'hlw2326/think-plugin-mp';

    /**
     * 注册模块菜单
     */
    public static function menu(): array
    {
        $code = app(static::class)->appCode;
        return [
            ['name' => '数据概览', 'icon' => 'layui-icon layui-icon-chart', 'node' => "{$code}/main/index"],
            ['name' => '小程序管理', 'icon' => 'layui-icon layui-icon-app', 'node' => "{$code}/mp/index"],
            ['name' => '广告配置', 'icon' => 'layui-icon layui-icon-rmb', 'node' => "{$code}/ad/index"],
            ['name' => '通知管理', 'icon' => 'layui-icon layui-icon-notice', 'node' => "{$code}/notice/index"],
            ['name' => '用户管理', 'icon' => 'layui-icon layui-icon-user', 'node' => "{$code}/user/index"],
            ['name' => '查询记录', 'icon' => 'layui-icon layui-icon-log', 'node' => "{$code}/user/query"],
            ['name' => '积分日志', 'icon' => 'layui-icon layui-icon-form', 'node' => "{$code}/user/score_log"],
            ['name' => '登录日志', 'icon' => 'layui-icon layui-icon-release', 'node' => "{$code}/user/token"],
            ['name' => '积分卡密', 'icon' => 'layui-icon layui-icon-survey', 'node' => "{$code}/card/score/index"],
            ['name' => '会员卡密', 'icon' => 'layui-icon layui-icon-vip', 'node' => "{$code}/card/vip/index"],
            ['name' => '充值记录', 'icon' => 'layui-icon layui-icon-list', 'node' => "{$code}/card/log/index"],
            ['name' => '系统配置', 'icon' => 'layui-icon layui-icon-set-fill', 'node' => "{$code}/config/index"],
        ];
    }
}
