<?php
declare(strict_types=1);

namespace plugin\mp\model;

use think\admin\Model;

/**
 * 小程序模型
 * @property int $id
 * @property string $name 小程序名称
 * @property string $appid 小程序AppID
 * @property string $appsecret 小程序密钥
 * @property int $sort 排序权重
 * @property int $status 状态(0禁用,1启用)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @class PluginMiniMp
 * @package plugin\mp\model
 */
class PluginMiniMp extends Model
{
}
