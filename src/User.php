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

    public function getFriendshipRequests() {

        $friendshipRequests = [];
        foreach ($this->friendshipsList as $friendship) {
            if ($friendship->status == "pending") {

                $friendshipRequests[] = $friendship;
            }
        }
        return $friendshipRequests;
    }

    public function acceptFriendshipRequest(User $sender) {

        // $requests = $this->getFriendshipRequests();
        
        // array_search()
        
    }
    


}