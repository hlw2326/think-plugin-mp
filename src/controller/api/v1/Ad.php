<?php
declare(strict_types=1);

namespace plugin\mp\controller\api\v1;

use plugin\mp\model\PluginMiniAd;
use think\admin\Controller;

/**
 * 广告配置接口
 * @class Ad
 * @package plugin\mp\controller\api\v1
 */
class Ad extends Controller
{
    /**
     * 获取指定小程序的广告单元配置
     * GET /api/v1/ad/config?appid=xxx
     */
    public function config(): void
    {
        $appid = $this->request->get('appid', '');
        if (empty($appid)) {
            $this->error('appid 不能为空');
        }

        $list = PluginMiniAd::mk()
            ->where('appid', $appid)
            ->where('status', 1)
            ->order('sort desc, id asc')
            ->field('id,name,unit_id,ad_type,position,extra')
            ->select()->toArray();

        $map = [];
        foreach ($list as &$item) {
            $item['extra'] = $item['extra'] ? json_decode($item['extra'], true) : [];
            $map[$item['position']] = $item;
        }

        $this->success('获取成功', ['list' => $list, 'map' => $map]);
    }
}
