<?php

declare(strict_types=1);

namespace plugin\mp\controller;

use think\admin\Controller;

/**
 * 常规配置
 * @class Config
 * @package plugin\mp\controller
 */
class Config extends Controller
{
    /**
     * 常规配置
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        $this->title = "常规配置";
        if ($this->request->isPost()) {
            $post = $this->request->post();
            foreach ($post as $key => $value) {
                sysconf($key, $value);
            }
            $this->success('配置保存成功！');
        } else {
            $this->fetch();
        }
    }
}
