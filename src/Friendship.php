<?php

namespace Karolina\App;

class Friendship {

    public User $sender;
    public User $receiver;
    public string $status;

    public function __construct(User $sender, User $receiver) {

        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->status = "pending";
    }







}