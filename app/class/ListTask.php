<?php


/**
 * Class ListTask
 */
class ListTask implements MSG
{
    /**
     * @var int
     */
    public int $count;
    /**
     * @var int
     */
    public int $page;
    /**
     * @var int
     */
    public int $countPage;
    /**
     * @var int
     */
    public int $admin;
    /**
     * @var array
     */
    public array $list;
}