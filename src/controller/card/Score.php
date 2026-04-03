<?php
declare(strict_types=1);

namespace plugin\mp\controller\card;

use plugin\mp\model\PluginMiniCardScore;
use plugin\mp\service\CardUseService;
use think\admin\Controller;
use think\admin\extend\CodeExtend;
use think\admin\helper\QueryHelper;
use think\admin\service\AdminService;

/**
 * 积分卡密管理
 * @class Score
 * @package plugin\mp\controller\card
 */
class Score extends Controller
{
    /**
     * 积分卡密
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        PluginMiniCardScore::mQuery()->layTable(function () {
            $this->title = '积分卡密';
        }, function (QueryHelper $query) {
            $query->like('card_code,batch_no,remark');
            $query->equal('status,max_users');
            $query->dateBetween('create_at');
            $query->timeBetween('expire_time');
            $get = $this->request->get();
            if (isset($get['user_limit_min']) && $get['user_limit_min'] !== '') {
                $query->db()->where('user_limit', '>=', intval($get['user_limit_min']));
            }
            if (isset($get['user_limit_max']) && $get['user_limit_max'] !== '') {
                $query->db()->where('user_limit', '<=', intval($get['user_limit_max']));
            }
        });
    }

    /**
     * 添加积分卡密
     * @auth true
     */
    public function add(): void
    {
        $this->_applyFormToken();
        PluginMiniCardScore::mForm('form');
    }

    /**
     * 编辑积分卡密
     * @auth true
     */
    public function edit(): void
    {
        $this->_applyFormToken();
        PluginMiniCardScore::mForm('form');
    }

    /**
     * 表单数据处理
     */
    protected function _form_filter(array &$data): void
    {
        if ($this->request->isPost()) {
            $data['expire_time'] = !empty($data['expire_time']) ? strtotime($data['expire_time']) : 0;
            if (empty($data['id'])) {
                $data['admin_id'] = AdminService::getUserId();
                if (empty($data['card_code'])) {
                    $data['card_code'] = $this->_genCode();
                }
                $exists = PluginMiniCardScore::mk()->where(['card_code' => $data['card_code']])->count();
                if ($exists > 0) {
                    $this->error('卡密已存在，请更换！');
                }
            }
        } else {
            if (empty($data['batch_no'])) {
                $data['batch_no'] = CodeExtend::uniqidDate(16, 'P');
            }
            if (!empty($data['expire_time'])) {
                $data['expire_time'] = date('Y-m-d', $data['expire_time']);
            } else {
                $data['expire_time'] = '';
            }
        }
    }

    /**
     * 批量生成表单过滤
     */
    protected function _form_batch_filter(array &$data): void
    {
        if (empty($data['batch_no'])) {
            $data['batch_no'] = CodeExtend::uniqidDate(16, 'P');
        }
        $data['expire_time'] = '';
    }

    /**
     * 批量生成积分卡密
     * @auth true
     */
    public function batch(): void
    {
        if ($this->request->isPost()) {
            $data = $this->_vali([
                'batch_no.require'      => '批次号不能为空！',
                'count.require'         => '生成数量不能为空！',
                'count.integer'         => '生成数量必须是整数！',
                'count.between:1,1000'  => '生成数量须在1-1000之间！',
                'score.require'         => '积分数量不能为空！',
                'score.integer'         => '积分数量必须是整数！',
                'score.egt:1'           => '积分数量不能少于1！',
                'max_users.integer'     => '最大用户数必须是整数！',
                'user_limit.require'    => '用户使用次数不能为空！',
                'user_limit.integer'    => '用户使用次数必须是整数！',
                'user_limit.egt:1'      => '用户使用次数不能少于1！',
            ]);
            $expireTime = !empty($data['expire_time']) ? strtotime($data['expire_time']) : 0;
            $adminId    = AdminService::getUserId();
            $count      = intval($data['count']);
            $inserted   = 0;
            for ($i = 0; $i < $count; $i++) {
                $code = $this->_genCode();
                PluginMiniCardScore::mk()->save([
                    'batch_no'    => $data['batch_no'],
                    'admin_id'    => $adminId,
                    'card_code'   => $code,
                    'score'       => intval($data['score']),
                    'max_users'   => intval($data['max_users'] ?? 1),
                    'user_limit'  => intval($data['user_limit']),
                    'expire_time' => $expireTime,
                    'remark'      => $data['remark'] ?? '',
                    'status'      => 1,
                ]);
                $inserted++;
            }
            $this->success("成功生成 {$inserted} 张积分卡密！");
        } else {
            PluginMiniCardScore::mForm('batch');
        }
    }

    /**
     * 修改状态
     * @auth true
     */
    public function state(): void
    {
        PluginMiniCardScore::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 使用积分卡密
     * @auth true
     */
    public function apply(): void
    {
        if ($this->request->isPost()) {
            $data = $this->_vali([
                'id.require'        => '卡密ID不能为空！',
                'user_id.require'   => '用户ID不能为空！',
                'user_id.integer'   => '用户ID必须是整数！',
                'user_id.egt:1'     => '用户ID不能小于1！',
            ]);
            $card = PluginMiniCardScore::mk()->where(['id' => $data['id']])->findOrEmpty();
            if (!$card->isExists()) {
                $this->error('卡密不存在！');
            }
            $result = CardUseService::useScoreCard($card->card_code, intval($data['user_id']));
            if ($result['success']) {
                $this->success($result['message']);
            } else {
                $this->error($result['message']);
            }
        } else {
            PluginMiniCardScore::mForm('apply');
        }
    }

    /**
     * 删除积分卡密
     * @auth true
     */
    public function remove(): void
    {
        PluginMiniCardScore::mDelete();
    }

    /**
     * 生成唯一卡密码
     */
    private function _genCode(): string
    {
        do {
            $code = strtoupper(implode('-', str_split(substr(md5(uniqid((string)mt_rand(), true)), 0, 16), 4)));
        } while (PluginMiniCardScore::mk()->where(['card_code' => $code])->count() > 0);
        return $code;
    }
}
