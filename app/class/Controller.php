<?php


/**
 * Class Controller
 */
class Controller
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
     * @param int $id
     * @param string $user
     * @param string $email
     * @param string $context
     * @param int $status
     * @return Task
     */
    public function makeTask(int $id, string $user, string $email, string $context, int $status = 0): Task
    {
        $task = new Task();
        $task->id = $id;
        $task->user = $user;
        $task->email = $email;
        $task->context = $context;
        $task->status = $status;
        return $task;
    }

    /**
     * @param array $result
     * @param Task $task
     * @return ActionEcho
     */
    public function makeActionEcho(array $result, Task $task): ActionEcho
    {
        $actionEcho = new ActionEcho();

        if ($result['errorCode'] == '00000') {

            $actionEcho->task = $task;
            $actionEcho->status = 0;
            $actionEcho->retmsg = "Ok";

        } else {
            $actionEcho->retmsg = $result['errorInfo'];
            $actionEcho->status = $result['errorCode'];
        }

        return $actionEcho;
    }

    /**
     * @param string $user
     * @param string $email
     * @param string $context
     * @return ActionEcho
     */
    public function addTask(string $user, string $email, string $context)
    {
        $task = $this->makeTask(0, $user, $email, $context);
        $result = $this->model->insertValue($task);

        if ($result['errorCode'] == '00000') $task->id = $result['insertId'];

        return $this->makeActionEcho($result, $task);
    }


    /**
     * @param int $id
     * @param string $user
     * @param string $email
     * @param string $context
     * @param int $status
     * @return ActionEcho
     * @throws ReflectionException
     */
    public function updateTask(int $id, string $user, string $email, string $context, int $status)
    {
        $task = $this->makeTask($id, $user, $email, $context, $status);

        $result = $this->model->updateValue($task);

        return $this->makeActionEcho($result, $task);
    }

    /**
     * @param int $id
     * @return ActionEcho
     */
    public function deleteTask(int $id)
    {
        $task = new Task();
        $result = $this->model->deleteValue('Task',$id);
        return $this->makeActionEcho($result, $task);
    }
}