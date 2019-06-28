<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 17-12-23
 * Time: 下午12:44
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Controller;
use Common\Util\Think\Page;


class PayTypeController extends AdminController{
    /*
     * 五码合一列表
     * */
    public function index(){
        $map['status'] = array('egt', '0');
        $wmhy=D('Paywm');
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list=$wmhy
            ->page($p,C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('status desc,sort desc,id desc')
            ->select();
        $page = new Page(
            $wmhy->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('五码合一列表') // 设置页面标题
        ->addTopButton('addnew')  // 添加新增按钮
        ->addTopButton('resume')  // 添加启用按钮
        ->addTopButton('forbid')  // 添加禁用按钮
        ->addTopButton('delete')  // 添加删除按钮
        ->addTableColumn('id', 'PID')
            ->addTableColumn('name', '类型')
            ->addTableColumn('img_src', '二维码','picture',null,true)
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('minmoney', '最小金额', 'minmoney')
            ->addTableColumn('maxmoney', '最大金额', 'maxmoney')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list)    // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit')          // 添加编辑按钮
            ->addRightButton('forbid')        // 添加禁用/启用按钮
            ->addRightButton('delete')        // 添加删除按钮
            ->display();
    }

    public function add(){

        if(IS_POST){
            $api_obj=D('Paywm');
            $data = $api_obj->create();
            if ($data) {
                $data['img_src']=isset($_SESSION['imginfo']) ? $_SESSION['imginfo'] : '';
                $id = $api_obj->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                    $_SESSION['erweima']='';
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($api_obj->getError());
            }
        }else{
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') //设置页面标题
            ->setPostUrl(U('add'))    //设置表单提交地址
            ->addFormItem('cid', 'select', '接口类型', '接口支付类型',array('3'=>'支付宝','1'=>'微信','2'=>'网银','4'=>'QQ','8'=>'银联扫码','9'=>'五码合一'))
                ->addFormItem('img_src', 'picture', '支付二维码', '支付的二维码')
                ->addFormItem('sort', 'text', '排序值', '(越大越靠前)')
                ->addFormItem('status', 'select', '状态', '',array('1'=>'启用','0'=>'禁用'))
                ->addFormItem('minmoney', 'text', '最小金额', '(最小金额)')
                ->addFormItem('maxmoney', 'text', '最大金额', '(最大金额)')
                ->display();
        }

    }

    public function edit($id){
        // 获取账号信息
        $info = D('Paywm')->find($id);
        if (IS_POST) {
            // 提交数据
            $api_obj = D('Paywm');
            $data = $api_obj->create();
            if ($data) {
                $data['img_src']=empty($_SESSION['imginfo']) && isset($info['img_src']) && !empty($info['img_src']) ? $info['img_src'] : $_SESSION['imginfo'];
                $result = $api_obj->save($data);
                if ($result) {
                    $_SESSION['imginfo']='';
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $api_obj->getError());
                }
            } else {
                $this->error($api_obj->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();

            $builder->setMetaTitle('编辑五码合一') //设置页面标题
            ->setPostUrl(U('edit'))    //设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('cid', 'select', '接口类型', '接口支付类型',array('3'=>'支付宝','1'=>'微信','2'=>'网银','4'=>'QQ','8'=>'银联扫码','9'=>'五码合一'))
                ->addFormItem('img_src', 'picture', '支付二维码', '支付的二维码')
                ->addFormItem('sort', 'text', '排序值', '(越大越靠前)')
                ->addFormItem('status', 'select', '状态', '',array('1'=>'启用','0'=>'禁用'))
                ->addFormItem('minmoney', 'text', '最小金额', '(最小金额)')
                ->addFormItem('maxmoney', 'text', '最大金额', '(最大金额)')
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     */
    public function setStatus($model = CONTROLLER_NAME) {
        $ids    = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        $model_primary_key = D('Paywm')->getPk();
        $map[$model_primary_key] = array('in',$ids);
        switch ($status) {
            case 'delete' :  // 删除条目
                if (!is_array($ids)) {
                    $id_list[0] = $ids;
                } else {
                    $id_list = $ids;
                }
                foreach ($id_list as $id) {
                    $res = D('Paywm')->delete($id);
                    $res ? $this->success('删除成功！') : $this->error('删除失败！');
                }
                break;
            case 'forbid' :  // 禁用条目
                $data = array('status' => 0);
                $this->editRow(
                    'Paywm',
                    $data,
                    $map,
                    array('success'=>'禁用成功','error'=>'禁用失败')
                );
                break;
            case 'resume' :  // 启用条目
                $data = array('status' => 1);
                $this->editRow(
                    'Paywm',
                    $data,
                    $map,
                    array('success'=>'启用成功','error'=>'启用失败')
                );
                break;
            default :
                parent::setStatus($model);
                break;
        }
    }


}