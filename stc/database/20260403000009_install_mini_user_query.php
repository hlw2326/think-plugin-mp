<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniUserQuery extends Migrator
{
    /**
     * 创建用户查询记录表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_user_query', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-用户查询记录',
        ]);

        PhinxExtend::upgrade($table, [
            ['user_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '查询用户ID']],
            ['platform', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '平台标识']],
            ['input_url', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '用户输入的原始链接']],
            ['query_result', 'text', ['default' => null, 'null' => true, 'comment' => '查询结果JSON']],
            ['cost_score', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '本次扣除积分（VIP免费=0）']],
            ['is_vip', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '查询时是否VIP(0否,1是)']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '查询状态(0失败,1成功)']],
            ['fail_reason', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '失败原因']],
            ['refunded', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '是否已退积分(0否,1是)']],
            ['refund_at', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '退积分时间']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '查询时间']],
        ], [
            'user_id',
            'platform',
            'status',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_user_query')->drop();
    }
}
