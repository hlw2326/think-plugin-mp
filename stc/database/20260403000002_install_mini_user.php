<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniUser extends Migrator
{
    /**
     * 创建小程序用户表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_user', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-小程序用户',
        ]);

        PhinxExtend::upgrade($table, [
            ['id', 'string', ['limit' => 32, 'default' => '', 'null' => true, 'comment' => '用户ID（租户级唯一）']],
            ['openid', 'string', ['limit' => 64, 'default' => '', 'null' => true, 'comment' => '微信OpenID']],
            ['pid', 'string', ['limit' => 32, 'default' => '', 'null' => true, 'comment' => '推荐人用户ID']],
            ['unionid', 'string', ['limit' => 64, 'default' => '', 'null' => true, 'comment' => '微信UnionID']],
            ['nickname', 'string', ['limit' => 255, 'default' => '', 'null' => true, 'comment' => '用户昵称']],
            ['avatar_url', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '用户头像']],
            ['gender', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '性别(0未知,1男,2女)']],
            ['phone', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '手机号']],
            ['score', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '积分']],
            ['vip_time', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '会员过期时间戳(0未开通)']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(1正常,0禁用)']],
            ['deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(1已删除,0正常)']],
            ['login_ip', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '本次登录IP']],
            ['last_login_ip', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '上次登录IP']],
            ['login_at', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '本次登录时间']],
            ['last_login_at', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '上次登录时间']],
            ['remark', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '备注']],
            ['device_model', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '手机型号']],
            ['device_system', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '操作系统版本']],
            ['screen_width', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '屏幕宽度(px)']],
            ['screen_height', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '屏幕高度(px)']],
            ['sdk_version', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '微信基础库版本']],
            ['app_version', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '小程序版本号']],
            ['app_channel', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '小程序来源渠道']],
            ['extra', 'text', ['default' => null, 'null' => true, 'comment' => '扩展字段(JSON)']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '注册时间']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'openid',
            'unionid',
            'phone',
            'status',
            'deleted',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_user')->drop();
    }
}
