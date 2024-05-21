<?php

namespace addons\fastexport\library;

use think\Db;

/**
 *
 */
class ExportLib
{
    protected $_error = '';
    protected $task;// 模型
    public    $fields = [];

    public function __construct($task)
    {
        if (!$task) {
            $this->setError('导出任务找不到啦~');
            return false;
        }
        $this->task = $task;

        // 准备所有的字段数据
        if ($this->task['field_config'] && isset($this->task['field_config']['title'])) {
            foreach ($this->task['field_config']['title'] as $key => $value) {
                $asName                            = $this->task['main_table'] . '.' . $key . ' as ' . $this->task['main_table'] . '_' . $key;
                $this->fields[$asName]['title']    = $value;
                $this->fields[$asName]['discerns'] = $this->task['field_config']['discerns'][$key];
                $this->fields[$asName]['scheme']   = $this->task['field_config']['scheme'][$key];
                $this->fields[$asName]['table']    = $this->task['main_table'];
                $this->fields[$asName]['field']    = $this->task['main_table'] . '_' . $key;
            }
        }

        if (isset($this->task['join_table']) && is_array($this->task['join_table'])) {
            foreach ($this->task['join_table'] as $key => $value) {
                if (isset($this->task['join_table'][$key]['fields']) && isset($this->task['join_table'][$key]['fields']['title'])) {
                    $joinTableName = $value['join_as'] ? $value['join_as'] : $value['table'];
                    foreach ($this->task['join_table'][$key]['fields']['title'] as $fkey => $fvalue) {
                        $asName                            = $joinTableName . '.' . $fkey . ' as ' . $joinTableName . '_' . $fkey;
                        $this->fields[$asName]['title']    = $fvalue;
                        $this->fields[$asName]['discerns'] = $this->task['join_table'][$key]['fields']['discerns'][$fkey];
                        $this->fields[$asName]['scheme']   = $this->task['join_table'][$key]['fields']['scheme'][$fkey];
                        $this->fields[$asName]['table']    = $joinTableName;
                        $this->fields[$asName]['field']    = $joinTableName . '_' . $fkey;
                    }
                }
            }
        }
    }

    public static function assignment($fieldValue, $scheme)
    {
        if ($scheme) {
            $scheme = explode(',', $scheme);
            foreach ($scheme as $value) {
                list($itemKey, $itemValue) = explode('=', $value);
                if ($itemKey == $fieldValue) {
                    return $itemValue;
                }
            }
        }
        return $fieldValue;
    }

    public static function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getXlsTitle(): array
    {
        $title = [];
        foreach ($this->fields as $key => $value) {
            $title[] = $value['title'] ?: $key;
        }
        return $title;
    }

    /**
     * 构建SQL
     * @param string $buildType count=直接查询总数,limit=读取指定范围的记录,test=测试SQL(读取前10条记录)
     * @param array  $limit     [mim, max]查询范围
     * @return string        SqlString
     */
    public function buildSql(string $buildType = 'count', array $limit = [0, 10]): string
    {
        $field     = '';// 字段
        $joinTable = []; // 关联表
        $where     = [];
        $order     = [];

        // 要查询的字段
        foreach ($this->fields as $key => $value) {
            $field .= $key . ',';
        }
        $field = trim($field, ',');

        // 关联表
        if (isset($this->task['join_table']) && is_array($this->task['join_table'])) {
            foreach ($this->task['join_table'] as $key => $value) {

                if ($value['table'] && $value['foreign_key'] && $value['local_key']) {
                    $joinTableName = $value['join_as'] ?: $value['table'];

                    $join      = $value['join_as'] ? $value['table'] . ' ' . $value['join_as'] : $value['table'];
                    $condition = vsprintf('%s.%s = %s.%s', [
                        $this->task['main_table'],
                        $value['foreign_key'],
                        $joinTableName,
                        $value['local_key']
                    ]);

                    $joinTable[] = [$join, $condition, $value['join_type']];
                }
            }
        }


        // 筛选
        if (isset($this->task['where_field']['op']) && is_array($this->task['where_field']['op'])) {
            foreach ($this->task['where_field']['op'] as $key => $expression) {
                if (isset($this->task['where_field']['condition'][$key])) {
                    $where[$key] = [$expression, $this->task['where_field']['condition'][$key]];
                }
            }
        }

        // 排序
        if ($this->task['order_field'] && $this->task['order_type']) {
            $order = [
                $this->task['order_field'] => $this->task['order_type']
            ];
        }

        $res = Db::table($this->task['main_table'])->field($field)->join($joinTable)->where($where);

        if ($buildType == 'count') {
            return $res->count();
        } elseif ($buildType == 'limit') {
            return $res->order($order)->limit($limit[0], $limit[1])->select(false);
        } elseif ($buildType == 'test') {
            return $res->order($order)->limit(10)->select(false);
        }

        return '';
    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return ExportLib
     */
    public function setError(string $error): ExportLib
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError(): string
    {
        return $this->_error ? __($this->_error) : '';
    }
}