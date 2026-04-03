<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniUserToken extends Migrator
{
    /**
     * 创建用户登录Token表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_user_token', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-用户登录Token',
        ]);

        PhinxExtend::upgrade($table, [
            ['user_id', 'string', ['limit' => 32, 'default' => '', 'null' => true, 'comment' => '用户ID']],
            ['token', 'string', ['limit' => 80, 'default' => '', 'null' => true, 'comment' => '登录Token']],
            ['device_model', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '手机型号']],
            ['device_system', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '操作系统版本']],
            ['screen_width', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '屏幕宽度(px)']],
            ['screen_height', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '屏幕高度(px)']],
            ['sdk_version', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '微信基础库版本']],
            ['app_version', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '小程序版本号']],
            ['app_channel', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '小程序来源渠道']],
            ['client_ip', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '登录IP']],
            ['login_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '登录时间']],
            ['expire_time', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => 'Token过期时间戳(0=不过期)']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(1有效,0失效)']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'user_id',
            'token' => ['unique' => true],
            'status',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_user_token')->drop();
    }
}
