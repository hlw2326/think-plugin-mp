<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniUserScoreLog extends Migrator
{
    /**
     * 创建用户积分流水表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_user_score_log', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-用户积分流水',
        ]);

        PhinxExtend::upgrade($table, [
            ['user_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '用户ID']],
            ['change', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '积分变动量（正=增加，负=扣除）']],
            ['balance', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '变动后积分余额']],
            ['source', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '来源类型']],
            ['source_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '来源关联ID（0=无关联）']],
            ['remark', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '备注说明']],
            ['admin_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '操作管理员ID（用户自触发=0）']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '记录时间']],
        ], [
            'user_id',
            'source',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_user_score_log')->drop();
    }
}
