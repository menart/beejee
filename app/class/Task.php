<?php


class Task
{
    public int $id = 0;
    public string $user;
    public string $email;
    public string $context;
    public int $status;

    public function __construct(string $user, string $email, string $context, int $status){
        $this->user = $user;
        $this->email = $email;
        $this->context = $context;
        $this->status = $status;
    }
}