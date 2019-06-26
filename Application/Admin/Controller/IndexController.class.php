<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 后台默认控制器
 * @author jry <93058680@qq.com>
 */
class IndexController extends AdminController {
    /**
     * 默认方法
     * @author jry <93058680@qq.com>
     */
    public function index(){
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 删除缓存
     * @author jry <93058680@qq.com>
     */
    public function removeRuntime() {
        $file = new \Common\Util\File();
        $result = $file->del_dir(RUNTIME_PATH);
        if ($result) {
            $this->success("缓存清理成功");
        } else {
            $this->error("缓存清理失败");
        }
    }
}
