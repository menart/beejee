<?php


/**
 * Class View
 */
class View
{
    /**
     * @var Model
     */
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
     * @param int $page
     * @param int $countTask
     * @param string $order
     * @param string $direction
     * @param int $admin
     * @return MSG
     * @throws ReflectionException
     */
    public function getListTask(int $page, int $countTask, string $order, string $direction, int $admin): MSG
    {

        $listtask = new ListTask();
        $listtask->count = (int)$this->model->GetCount('Task');
        $listtask->countPage = ceil($listtask->count / $countTask);

        if ($page >= $listtask->countPage)
            $page = $listtask->countPage - 1;

        $listtask->page = $page;

        $listtask->admin = $admin;

        $listtask->list = $this->model->getList('Task', $page, $countTask, $order, $direction);
        return $listtask;
    }
}