<?php

namespace Karolina\App;


class User {
    
    public string $username;
    public array $friendshipsList = [];

    public function __construct($username) {

        $this->username = $username;
    }
    
    public function getFriendships() {

        return $this->friendshipsList;
    }

    public function addFriend(User $friend) {

        $this->friendshipsList[] = new Friendship($this, $friend);
        $friend->friendshipsList[] = new Friendship($this, $friend);
    }


    


}