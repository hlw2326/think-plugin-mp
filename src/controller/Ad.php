<?php
declare(strict_types=1);

namespace plugin\mp\controller;

use plugin\mp\model\PluginMiniAd;
use plugin\mp\model\PluginMiniMp;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 广告管理
 * @class Ad
 * @package plugin\mp\controller
 */
class Ad extends Controller
{
    /**
     * 广告列表
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        $mpList = PluginMiniMp::mk()->where('status', 1)->column('name', 'appid');
        $appid  = trim(input('appid', ''));

        $tabs   = ['' => ['name' => lang('全部广告'), 'count' => 0]];
        foreach ($mpList as $app => $name) {
            $tabs[$app] = ['name' => $name, 'count' => 0];
        }
        $total = PluginMiniAd::mk()->group('appid')->column('count(id) as cnt,appid', 'appid');
        foreach ($total as $row) {
            $tabs['']['count'] += $row['cnt'];
            if (isset($tabs[$row['appid']])) {
                $tabs[$row['appid']]['count'] = $row['cnt'];
            }
        }

        PluginMiniAd::mQuery()->layTable(function () use ($tabs) {
            $this->title    = '广告列表';
            $this->ad_types = PluginMiniAd::getAdTypes();
            $this->tabs     = $tabs;
            $this->mp_list  = PluginMiniMp::mk()->where('status', 1)->column('name', 'appid');
        }, function (QueryHelper $query) use ($appid) {
            $query->like('name,unit_id,position,appid');
            $query->equal('ad_type,status');
            $query->dateBetween('create_at');
            if ($appid !== '') {
                $query->equal('appid', $appid);
            }
        });
    }

    /**
     * 添加广告
     * @auth true
     */
    public function add(): void
    {
        $this->_applyFormToken();
        $this->title = '添加广告';
        PluginMiniAd::mForm('form');
    }

    /**
     * 编辑广告
     * @auth true
     */
    public function edit(): void
    {
        $this->_applyFormToken();
        $this->title = '编辑广告';
        PluginMiniAd::mForm('form');
    }

    /**
     * 表单数据处理
     */
    protected function _form_filter(array &$data): void
    {
        $this->ad_types = PluginMiniAd::getAdTypes();
        $this->mp_list  = PluginMiniMp::mk()->where('status', 1)->column('name', 'appid');
    }

    /**
     * 修改状态
     * @auth true
     */
    public function state(): void
    {
        PluginMiniAd::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除广告
     * @auth true
     */
    public function remove(): void
    {
        PluginMiniAd::mDelete();
    }
}
