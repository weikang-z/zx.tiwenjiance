<?php

namespace app\api\library;

use think\Config;
use app\common\exception\UploadException;
use app\common\library\Upload;

trait F
{

    public function upload()
    {
        Config::set('default_return_type', 'json');
        //必须设定cdnurl为空,否则cdnurl函数计算错误
        Config::set('upload.cdnurl', '');
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            if (!Config::get('upload.chunking')) {
                $this->error(__('Chunk file disabled'));
            }
            $action = $this->request->post("action");
            $chunkindex = $this->request->post("chunkindex/d");
            $chunkcount = $this->request->post("chunkcount/d");
            $filename = $this->request->post("filename");
            $method = $this->request->method(TRUE);
            if ($action == 'merge') {
                $attachment = NULL;
                //合并分片文件
                try {
                    $upload = new Upload();
                    $attachment = $upload->merge($chunkid, $chunkcount, $filename);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success(__('Uploaded successful'), '', ['fullurl' => cdnurl($attachment->url, TRUE)]);
            } elseif ($method == 'clean') {
                //删除冗余的分片文件
                try {
                    $upload = new Upload();
                    $upload->clean($chunkid);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            } else {
                //上传分片文件
                //默认普通上传文件
                $file = $this->request->file('file');
                try {
                    $upload = new Upload($file);
                    $upload->chunk($chunkid, $chunkindex, $chunkcount);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            }
        } else {
            $attachment = NULL;
            //默认普通上传文件
            $file = $this->request->file('file');
            try {
                $upload = new Upload($file);
                $attachment = $upload->upload();
            } catch (UploadException $e) {
                $this->error($e->getMessage());
            }

            $this->success(__('Uploaded successful'), '', [ 'fullurl' => cdnurl($attachment->url, TRUE)]);
        }
    }

    /**
     * 拉起支付
     *
     * @param $params
     */
    protected function epay($params)
    {
        /**
         * params 结构
         * $params = [
         * 'amount' => $row->price,
         * 'orderid' => $order_sn,
         * 'type' => "wechat",
         * 'title' => "开通课程" . $row->title,
         * 'notifyurl' => request()->domain() . '/api/course/notify/paytype/wechat',
         * 'method' => "miniapp",
         * 'openid' => $this->member->openid,
         * ];
         */
        return \addons\epay\library\Service::submitOrder($params);
    }

    /**
     * 支付回调入口
     */
    protected function notify()
    {
        $bz = strtolower(\request()->module() . '@' . \request()->controller());
        $notifys = Config::get('notify');
        if (!isset($notifys[$bz])) {
            exit('Error: 未定义' . $bz . '的支付回调');
        }
        $paytype = $this->request->param('paytype');
        $pay = \addons\epay\library\Service::checkNotify($paytype);
        if (!$pay) {
            echo '签名错误';
            return;
        }
        $data = $pay->verify();
        try {
            $notifys[$bz]($data['out_trade_no']);
        } catch (\Exception $e) {
        }
        echo $pay->success();
    }
}
