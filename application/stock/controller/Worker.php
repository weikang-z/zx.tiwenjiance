<?php

namespace app\stock\controller;

use think\Cache;
use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'websocket://0.0.0.0:37911';

    protected array $connections = [];

    private static function cn($fm_id): string
    {
        return sprintf("tiwen_stock_data.%s", $fm_id);
    }

    public function onConnect($connection)
    {
        $connection->id = uniqid();
        $this->connections[$connection->id] = $connection;
    }

    public function onMessage($connection, $data)
    {
        try {
            $data = json_decode($data, true);
            $type = $data['type'] ?? null;
            if (!in_array($type, ['up', 'get'])) {
                throw new \Exception('type 参数错误');
            }
            $fm_id = $data['fm_id'] ?? null;
            if (empty($fm_id)) {
                throw new \Exception('fm_id 参数错误');
            }

            $connection->fm_id = $fm_id;

            if ($type == 'up') {
                $tiwen_data = $data['data'] ?? null;
                if (empty($tiwen_data)) {
                    throw new \Exception('data 参数错误');
                }

                $temp = [
                    'oc' => (float)$tiwen_data,
                    'of' => (floatval($tiwen_data) * 9 / 5) + 32
                ];

                Cache::set(self::cn($fm_id), $temp, 120);

                $connection->send(json_encode([
                    'code' => 200,
                ], 256));
            } elseif ($type == 'get') {
                $this->startBroadcast($connection, $fm_id);
            }
        } catch (\Exception $e) {
            $connection->send(json_encode([
                'code' => 500,
                'error' => $e->getMessage() . '(' . $e->getLine() . ')',
            ], 256));
        }
    }

    public function onClose($connection)
    {
        if (isset($this->connections[$connection->id])) {
            \Workerman\Lib\Timer::del($connection->timer_id);
            unset($this->connections[$connection->id]);
        }
    }

    public function onError($connection, $code, $msg)
    {
        if (isset($this->connections[$connection->id])) {
            \Workerman\Lib\Timer::del($connection->timer_id);
            unset($this->connections[$connection->id]);
        }
    }

    public function startBroadcast($connection, $fm_id)
    {
        $broadcast = function () use ($connection, $fm_id) {
            if (!isset($this->connections[$connection->id])) {
                return;
            }
            $data = Cache::get(self::cn($fm_id));
            $connection->send(json_encode([
                'code' => 200,
                'data' => $data,
            ], 256));
        };

        // 每秒广播一次数据
        $connection->timer_id = \Workerman\Lib\Timer::add(1, $broadcast);
    }
}