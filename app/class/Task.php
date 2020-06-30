<?php


class Task implements DBObject
{
    public int $id = 0;
    public string $user;
    public string $email;
    public string $context;
    public int $status = 0;
}