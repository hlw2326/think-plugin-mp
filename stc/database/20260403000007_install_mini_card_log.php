<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniCardLog extends Migrator
{
    /**
     * 创建卡密使用记录表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_card_log', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-卡密使用记录',
        ]);

        PhinxExtend::upgrade($table, [
            ['card_type', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '卡密类型(1Score,2会员)']],
            ['card_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '卡密ID']],
            ['card_code', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '卡密码']],
            ['user_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '使用用户ID']],
            ['use_time', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '使用时间戳']],
            ['use_data', 'text', ['default' => null, 'null' => true, 'comment' => '使用数据(JSON格式)']],
            ['ip_address', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '使用IP地址']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '创建时间']],
        ], [
            'card_type',
            'card_code',
            'user_id',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_card_log')->drop();
    }
}
