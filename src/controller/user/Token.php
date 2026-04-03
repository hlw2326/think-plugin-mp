<?php
declare(strict_types=1);

namespace plugin\mp\controller\user;

use plugin\mp\model\PluginMiniUserToken;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 用户登录设备管理
 * @class Token
 * @package plugin\mp\controller\user
 */
class Token extends Controller
{
    /**
     * 登录设备列表
     * @auth true
     */
    public function index(): void
    {
        PluginMiniUserToken::mQuery()->layTable(function () {
            $this->title = '登录设备';
        }, function (QueryHelper $query) {
            $query->like('user_id,device_model');
            $query->equal('status');
            $query->dateBetween('login_at');
        });
    }

    /**
     * 弹窗查看用户设备列表
     * @auth true
     */
    public function device(): void
    {
        $this->_applyFormToken();
        PluginMiniUserToken::mQuery()->layTable(function () {
            $this->title = '登录设备';
        }, function (QueryHelper $query) {
            $query->equal('user_id');
            $query->like('device_model,sdk_version,app_version,app_channel');
            $query->equal('status');
            $query->dateBetween('login_at');
        });
    }

    /**
     * 退出指定设备（销毁Token）
     * @auth true
     */
    public function remove(): void
    {
        $token = input('token', '');
        if (empty($token)) {
            $this->error('Token不能为空');
        }
        PluginMiniUserToken::mk()->where(['token' => $token])->update(['status' => 0]);
        sysoplog('用户管理', "退出登录设备 Token[$token]");
        $this->success('设备已退出登录');
    }

    /**
     * 退出全部设备
     * @auth true
     */
    public function clean(): void
    {
        $userId = input('user_id', '');
        if (empty($userId)) {
            $this->error('用户ID不能为空');
        }
        PluginMiniUserToken::mk()->where([
            'user_id' => $userId,
            'status'  => 1,
        ])->where(function ($q) {
            $q->where('expire_time', 0)->whereOr('expire_time', '>', time());
        })->update(['status' => 0]);
        sysoplog('用户管理', "退出用户[$userId]所有登录设备");
        $this->success('已退出所有设备');
    }

    /**
     * 清空所有设备（删除）
     * @auth true
     */
    public function destroy(): void
    {
        $userId = input('user_id', '');
        if (empty($userId)) {
            $this->error('用户ID不能为空');
        }
        PluginMiniUserToken::mk()->where(['user_id' => $userId])->delete();
        sysoplog('用户管理', "清空用户[$userId]所有登录设备");
        $this->success('已清空所有设备');
    }
}
