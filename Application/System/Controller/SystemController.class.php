<?php
/******************************************************************
** 文件名称: SystemController.class.php
** 功能描述: 模块基础类，公共逻辑
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Controller;
use Think\Controller;

class SystemController extends BaseController 
{

    /**
     * [_initialize 初始化方法]
     * @Author haodaquan
     * @Date   2016-04-03
     * @return [type]     []
     */
    public function _initialize()
    {
    	parent::_initialize();
    }

    /**
     * [index 默认首页]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    public function index()
    {
        $this->getPageTitle();
        $this->showSearch();
        $this->getShowFields();
        $this->getColumnsWidth();
        $this->getQueryUrl();

        $this->_displayMmgrids();
    }

    protected function getPageTitle()
    {
        if($this->pageTitle)
        {
            $this->assign('pageTitle',$this->pageTitle);
        }else{
            $this->assign('pageTitle','根据权限系统查询标题，待开发');
        }
    }

    /**
     * [_displayMmgrids 显示列表模板]
     * @Author haodaquan
     * @Date   2016-04-04
     * @return [type]     [description]
     */
    protected function _displayMmgrids()
    {
        $this->display('Grid/mmgrid');
    }

    /**
     * [showSearch 是否增加自定义搜索条件，true or false]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    protected function showSearch()
    {
       $this->assign('showSearch',true);
    }

    /**
     * [getShowFields 获取列表头]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    protected function getShowFields()
    {
        if (!empty($this->showFields)) {
            //return $this->showFields;
            $this->assign('showFields',json_encode($this->showFields));
        }
    }

    /**
     * [getColumnsWidth 获取列宽度，用于手工设定]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    protected function getColumnsWidth()
    {
        $columnsWidth = !empty($this->columnsWidth) ? $this->columnsWidth : array('');
        $this->assign('columnsWidth',json_encode($columnsWidth));      
    }

    /**
     * [getQueryUrl 获取列表查询地址]
     * @Author haodaquan
     * @Date   2016-04-06
     * @return [type]     [description]
     */
    protected function getQueryUrl()
    {
        $queryUrl = U(MODULE_NAME."/".CONTROLLER_NAME.'/query');
        if (!empty($this->queryUrl)) {
            $queryUrl = $this->queryUrl;
        } 
        $this->assign('queryUrl',$queryUrl);
    }

    /**
     * [query 查询列表数据]
     * @Author haodaquan
     * @Date   2016-04-07
     * @return [type]     [description]
     */
    public function query()
    {
        $data = D($this->modelName)->queryList($_POST);
        if($data['status']==200)
        {
            $data['data']['items'] = $this->listDataFormat($data['data']['items']);
            
            $this->ajaxReturn($data['data']);
        }
    }

    /**
     * [listDataFormat 列表数据格式化,子类一般需要重写]
     * @Author haodaquan
     * @Date   2016-04-07
     * @param  array      $data [description]
     * @return [type]           [description]
     */
    public function listDataFormat($listData)
    {
        if(empty($listData)) return array('');

        $listFormat = array();
        $buttons = array(
                'detail' => '查看',
                'edit'   => '编辑',
                'delete' => '删除'
            );
       
        foreach ($listData as $key => $value) 
        {
            $listFormat[$key] = $value;
            //数据的格式化及添加操作按钮
            if(isset($value['addtime'])){
                $listFormat[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
            }

            if(isset($value['edittime'])){
                $listFormat[$key]['edittime'] = date('Y-m-d H:i:s',$value['edittime']);
            }
            $listFormat[$key]['action'] = getButton($value['member_id'],$buttons);
        }
        return $listFormat;
    }

    /**
     * [detail 详情页]
     * @Author haodaquan
     * @Date   2016-04-08
     * @return [type]     [详情页]
     */
    public function detail()
    {
        $this->display();
    }


    /**
     * [input 编辑或者新增]
     * @Author haodaquan
     * @Date   2016-04-08
     * @return [type]     [description]
     */
    public function input()
    {
        $this->display();
    }

    /**
     * [delete 删除]
     * @Author haodaquan
     * @Date   2016-04-08
     * @return [type]     [description]
     */
    public function delete()
    {
        //逻辑
        $this->ajaxReturn(array('status'=>200,'message'=>'成功'));
    }



}