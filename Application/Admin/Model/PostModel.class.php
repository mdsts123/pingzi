<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Common\Model\ModelModel;
/**
 * 文章模型
 * @author jry <93058680@qq.com>
 */
class PostModel extends ModelModel {
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <93058680@qq.com>
     */
    protected $tableName = 'admin_post';

    /**
     * 自动验证规则
     * @author jry <93058680@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,80', '标题长度为1-80个字符', self::EXISTS_VALIDATE, 'length'),
        array('title', '', '标题已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <93058680@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <93058680@qq.com>
     */
    protected function _after_find(&$result, $options) {
       if ($result['cover'] >= 0) {
           $result['cover_url'] = get_cover($result['cover'], 'default');
       }
    }

    /**
     * 查找后置操作
     * @author jry <93058680@qq.com>
     */
    protected function _after_select(&$result, $options) {
        foreach($result as &$record){
            $this->_after_find($record, $options);
        }
    }

    /**
     * 获取列表
     * @author jry <93058680@qq.com>
     */
    public function getList($cid, $limit = 10, $page = 1, $order = null, $map = null) {
        $con["status"] = array("eq", '1');
        $con["cid"]    = array("eq", $cid);
        if ($map) {
            $map = array_merge($con, $map);
        }
        if (!$order) {
            $order = 'sort desc, id desc';
        }
        $list = $this->page($page, $limit)
                          ->order($order)
                          ->where($map)
                          ->select();

        return $list;
    }
}
