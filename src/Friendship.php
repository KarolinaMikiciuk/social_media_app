<?php

namespace Karolina\App;

class Friendship {

    public User $sender;
    public User $receiver;
    public string $status;

    public function __construct(User $sender, User $receiver)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->status = "pending";
    }

    public function getFriendOf(User $user): User
    {
        return $user === $this->sender ? $this->receiver : $this->sender;
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted'; 
    }
}