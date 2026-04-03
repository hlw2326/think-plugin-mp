<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniMp extends Migrator
{
    /**
     * 创建小程序配置表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_mp', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-小程序配置',
        ]);

        PhinxExtend::upgrade($table, [
            ['name', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '小程序名称']],
            ['appid', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '小程序AppID']],
            ['appsecret', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '小程序密钥']],
            ['sort', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '排序权重']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(0禁用,1启用)']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '创建时间']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'appid' => ['unique' => true],
            'status',
            'sort',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_mp')->drop();
    }
}
