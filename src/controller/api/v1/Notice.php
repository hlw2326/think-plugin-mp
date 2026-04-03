<?php
declare(strict_types=1);

namespace plugin\mp\controller\api\v1;

use plugin\mp\model\PluginMiniNotice;
use think\admin\Controller;

/**
 * 通知公告接口
 * @class Notice
 * @package plugin\mp\controller\api\v1
 */
class Notice extends Controller
{
    /**
     * 获取通知列表
     * GET /api/v1/notice/list?appid=xxx&type=xxx
     */
    public function list(): void
    {
        $appid = $this->request->get('appid', '');
        $type  = $this->request->get('type', '');

        $query = PluginMiniNotice::mk()->where('status', 1)->where('appid', $appid);
        if ($type !== '') {
            $query->where('type', $type);
        }
        $list = $query->order('sort desc,id desc')->select()->toArray();

        $this->success('获取成功', ['list' => $list]);
    }
}
