<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniCardScore extends Migrator
{
    /**
     * 创建积分卡密表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_card_score', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-积分卡密',
        ]);

        PhinxExtend::upgrade($table, [
            ['batch_no', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '批次号']],
            ['admin_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '创建管理员ID']],
            ['card_code', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '卡密码']],
            ['score', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '积分数量']],
            ['max_users', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '最大使用用户数(0无限制)']],
            ['user_limit', 'integer', ['limit' => 11, 'default' => 1, 'null' => true, 'comment' => '每用户使用次数限制']],
            ['expire_time', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '卡密到期时间戳(0永不过期)']],
            ['remark', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '备注']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(0禁用,1启用)']],
            ['sort', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '排序权重']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '创建时间']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'batch_no',
            'card_code' => ['unique' => true],
            'status',
            'sort',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_card_score')->drop();
    }
}
