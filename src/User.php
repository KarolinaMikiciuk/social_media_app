<?php

namespace Karolina\App;


class User {
    
    public string $username;
    public array $friendsList = [];

    public function __construct($username) {

        $this->username = $username;
    }
    
    public function getFriendships() {

        return $this->friendsList;
    }

    public function addFriend(User $friend) {

        $this->friendsList[] = $friend;
        $friend->friendsList[] = $this;
    }


    


}