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
        
        $commonFriendship = new Friendship($this, $friend);
        $this->friendshipsList[] = $commonFriendship;
        $friend->friendshipsList[] = $commonFriendship;
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

        $requestedFriendships = $this->getFriendshipRequests();

        foreach ($requestedFriendships as $requestedFriendship) {

            if ($requestedFriendship->sender == $sender) {
                $friendshipRequestToAccept = $requestedFriendship;
                break;
            } 
        }
        if (! $friendshipRequestToAccept) {
            throw new Exception("No friendship request sent from this user.");
        } else {
            $friendshipRequestToAccept->status = "accepted";
        }
    }
    


}