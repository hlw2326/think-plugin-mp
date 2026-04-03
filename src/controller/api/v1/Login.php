<?php
declare(strict_types=1);

namespace plugin\mp\controller\api\v1;

use plugin\mp\model\PluginMiniMp;
use think\admin\Controller;
use WeMini\Crypt;

/**
 * 登录接口
 * @class Login
 * @package plugin\mp\controller\api\v1
 */
class Login extends Controller
{
    public function in(): void
    {
        if ($this->request->isPost()) {
            $code  = $this->request->post('code');
            $appid = $this->request->get('appid');
            $mp = PluginMiniMp::mk()->where(['appid' => $appid])->findOrEmpty();
            $config = [
                'appid'          => $mp->appid,
                'appsecret'      => $mp->appsecret,
                'encodingaeskey' => '',
            ];
            $crypt = Crypt::instance($config);
            $this->success('登录成功', $crypt);
        }
    }
}
