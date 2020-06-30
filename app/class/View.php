<?php


class View
{
    private Model $model;

    /**
     * View constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getListTask(int $page, int $counttask, string $order, int $admin):MSG
    {

        $listtask = new ListTask();
        $listtask->count = (int)$this->model->GetCount('Task');
        $listtask->countPage = ceil($listtask->count/$counttask);

        if($page >= $listtask->countPage)
            $page = $listtask->countPage-1;

        $listtask->page = $page;

        $listtask->admin = $admin;

        $listtask->list = $this->model->getList('Task',$page, $counttask, $order);
        return $listtask;
    }
}