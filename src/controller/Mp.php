<?php
declare(strict_types=1);

namespace plugin\mp\controller;

use plugin\mp\model\PluginMiniMp;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 小程序管理
 * @class Mp
 * @package plugin\mp\controller
 */
class Mp extends Controller
{
    /**
     * 小程序列表
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        PluginMiniMp::mQuery()->layTable(function () {
            $this->title = '小程序列表';
        }, function (QueryHelper $query) {
            $query->like('name,appid');
            $query->equal('status');
        });
    }

    /**
     * 添加小程序
     * @auth true
     */
    public function add(): void
    {
        $this->_applyFormToken();
        PluginMiniMp::mForm('form');
    }

    /**
     * 编辑小程序
     * @auth true
     */
    public function edit(): void
    {
        $this->_applyFormToken();
        PluginMiniMp::mForm('form');
    }

    /**
     * 修改状态
     * @auth true
     */
    public function state(): void
    {
        PluginMiniMp::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除小程序
     * @auth true
     */
    public function remove(): void
    {
        PluginMiniMp::mDelete();
    }
}
