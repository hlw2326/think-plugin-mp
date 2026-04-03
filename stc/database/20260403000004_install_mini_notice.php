<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallMiniNotice extends Migrator
{
    /**
     * 创建通知公告表
     */
    public function up(): void
    {
        $table = $this->table('plugin_mini_notice', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-通知公告',
        ]);

        PhinxExtend::upgrade($table, [
            ['appid', 'string', ['limit' => 200, 'default' => '*', 'null' => true, 'comment' => '目标小程序（* 全部，多个用逗号分隔）']],
            ['type', 'string', ['limit' => 30, 'default' => 'system', 'null' => true, 'comment' => '通知类型(system/activity/update)']],
            ['title', 'string', ['limit' => 200, 'default' => '', 'null' => true, 'comment' => '通知标题']],
            ['content', 'text', ['default' => null, 'null' => true, 'comment' => '通知内容']],
            ['jump_type', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '跳转类型']],
            ['jump_value', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '跳转值']],
            ['sort', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '排序权重']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(0禁用,1启用)']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '创建时间']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'type',
            'status',
            'sort',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_mini_notice')->drop();
    }
}
