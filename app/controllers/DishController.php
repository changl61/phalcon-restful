<?php

class DishController extends ControllerBase
{
    public function search()
    {
        $finder = DishModel::makeFinder($this->request);
        $pagination = Utils::paginate($finder, $this->request->get('page'), $this->request->get('pageSize'));

        return $this->respond($pagination);
    }

    public function detail($id)
    {
        $model = DishModel::findFirstById($id);
        if (!$model) throw new HttpException('无此记录', 404);

        return $this->respond([
            'detail' => $model->toArray(),
        ]);
    }

    public function create()
    {
        // 验证
        $form = new FormForDish($this->request->getPost(), 'create');
        if (!$form->validate()) throw new ValidationException($form->getErrorMsg(), 400);

        // 保存
        $model = new DishModel();
        $form->assignTo($model);
        if (!$model->save()) throw new DbException('保存失败', 500);

        return $this->respond([
            'detail' => $model->toArray(),
        ]);
    }

    public function update($id)
    {
        $model = DishModel::findFirstById($id);
        if (!$model) throw new HttpException('无此记录', 404);

        // 验证
        $form = new FormForDish($this->request->get(), 'update');
        $form->id = $id;
        if (!$form->validate()) throw new ValidationException($form->getErrorMsg(), 400);

        // 保存
        $form->assignTo($model);
        if (!$model->save()) throw new DbException('保存失败', 500);

        return $this->respond([
            'detail' => $model->toArray(),
        ]);
    }

    public function delete($id)
    {
        $model = DishModel::findFirstById($id);
        if (!$model) throw new HttpException('无此记录', 404);
        if (!$model->delete()) throw new HttpException('删除失败', 500);

        return $this->respond();
    }
}