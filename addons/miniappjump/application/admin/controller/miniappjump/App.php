<?php

namespace app\admin\controller\miniappjump;

use app\common\controller\Backend;

/**
 * 小程序管理
 *
 * @icon fa fa-circle-o
 */
class App extends Backend
{
    
    /**
     * App模型对象
     * @var \app\admin\model\miniappjump\App
     */
    protected $model = null;

    protected $searchFields = 'id,title';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\miniappjump\App;
        $this->view->assign("platformList", $this->model->getPlatformList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }    

    public function index()
    {
        // 设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $platform = $this->request->request("platform");

            // 如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            
            if (!empty($platform) && $platform != 'all') {
                $this->model->where(['platform' => $platform]);
            }

            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            if (!empty($platform) && $platform != 'all') {
                $this->model->where(['platform' => $platform]);
            }

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }

    /**
     * 生成小程序授权列表
     */
    public function app()
    {
        if ($this->request->isPost()) {
            $platform = $this->request->post('platform');

            if ($platform == 'aliapp') {
                $appidlistdata = '请到小程序管理后台开启“允许所有小程序跳转”或“指定小程序跳转”';
            } else {

                $appidlist = '';

                $app = $this->model->where(['platform' => $platform])->column('appid');

                foreach ($app as $key => $value) {
                    if ($key != 0) {
                        $appidlist .= ',';
                    }
                    $appidlist .= '"'.$value.'"';
                }

$appidlistdata = <<<EOT
"navigateToMiniProgramAppIdList": [
    #appidlist#
]
EOT;

                // 替换数据
                $appidlistdata = str_replace('#appidlist#', $appidlist, $appidlistdata);
            }

            echo $appidlistdata;

        } else {
            return $this->view->fetch();
        }        
    }
}
