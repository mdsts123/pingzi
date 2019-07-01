<?php
// +----------------------------------------------------------------------
// | 推广订单模型
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: andy <3297123230@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Common\Model\ModelModel;
/**
 * 推广订单模型
 * @author jry <93058680@qq.com>
 */
class TgOrderModel extends ModelModel {
    /**
     * 数据库表名
     * @author jry <93058680@qq.com>
     */
    protected $tableName = 'tg_order';

    /**
     * 自动验证规则
     * @author jry <93058680@qq.com>
     */
    protected $_validate = array(
        //验证提交类型
        array('commit_type', 'require', '提交类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //验证付款类型
        array('pay_type', 'require', '支付类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        // 验证会员账号
        array('username', 'require', '会员账号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('username', '3,32', '会员账号长度为1-32个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('reusername', 'username', '两次输入的会员账号不一致', self::EXISTS_VALIDATE, 'confirm', self::MODEL_INSERT),

    );

    /**
     * 自动完成规则
     * @author jry <93058680@qq.com>
     */
    protected $_auto = array(
        array('giv_amount', '0', self::MODEL_INSERT),
        array('pay_status', '0', self::MODEL_INSERT),
        array('cmit_time', 'time', self::MODEL_INSERT, 'function'),
    );





}
