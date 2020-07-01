<?php


/**
 * Class Task
 */
class Task implements DBObject
{
    /**
     * @var int
     */
    public int $id = 0;
    /**
     * @var string
     */
    public string $user;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $context;
    /**
     * @var int
     */
    public int $status = 0;
}