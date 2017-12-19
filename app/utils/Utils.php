<?php

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

/**
 * 工具类
 */
class Utils
{

    /**
     * 分页
     * @param  obj $finder
     * @param  int $page
     * @param  int $pageSize
     * @return arr
     */
    public static function paginate($finder, $page, $pageSize)
    {
        $page = $page ? (int)$page : 1;
        $pageSize = $pageSize ? (int)$pageSize : 10;

        $paginator = new PaginatorModel([
            'data'  => $finder,
            'page'  => $page,
            'limit' => $pageSize,
        ]);
        $pagination = $paginator->getPaginate();


        // 记录列表
        $list = [];
        foreach ($pagination->items as $model) {
            $list[] = $model->toArray();
        }

        return [
            'list' => $list,
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $pagination->total_items,
        ];
    }
}