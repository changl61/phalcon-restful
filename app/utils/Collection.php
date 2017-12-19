<?php

/**
 * 集合数据处理方法
 */
class Collection
{
    /**
     * 查找符合条件的纪录
     * @param  $collection 目标集合
     * @param  $conditions 过滤条件
     * @return array
     */
    public static function find($collection, $conditions)
    {
        $result = [];
        foreach ($collection as $record) {
            $match = true;

            foreach ($conditions as $k=>$v) {
                if (!isset($record[$k]) || $record[$k] != $v) {
                    $match = false;
                    break;
                }
            }

            if ($match) $result[] = $record;
        }

        return $result;
    }

    /**
     * 查找符合条件的第一个纪录
     * @param  $collection 目标集合
     * @param  $conditions 过滤条件
     * @return array
     */
    public static function findFirst($collection, $conditions)
    {
        $result = [];
        foreach ($collection as $record) {
            $match = true;

            foreach ($conditions as $k=>$v) {
                if (!isset($record[$k]) || $record[$k] != $v) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                $result = $record;
                break;
            }
        }

        return $result;
    }

    /**
     * 以某个字段作为索引
     * @param  $collection 目标集合
     * @param  $field      索引字段
     * @return array
     */
    public static function indexBy($collection, $field)
    {
        $result = [];
        foreach ($collection as $record) {
            $index = $record[$field];
            $result[$index] = $record;
        }

        return $result;
    }

    /**
     * 萃取数组对象中某属性值，返回一个数组
     * @param  $collection 目标集合
     * @param  $field      萃取字段
     * @return array
     */
    public static function pluck($collection, $field)
    {
        $result = [];

        foreach ($collection as $record) {
            $result[] = $record[$field];
        }

        return $result;
    }

    /**
     * 精简数组对象, 保留某些字段
     * @param  $collection 目标集合
     * @param  $fields     保留字段
     * @return array
     */
    public static function thin($collection, $fields)
    {
        $result = [];

        foreach ($collection as $record) {
            $item = [];
            foreach ($fields as $field) {
                $item[$field] = $record[$field];
            }
            $result[] = $item;
        }

        return $result;
    }
}