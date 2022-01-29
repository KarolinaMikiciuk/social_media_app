<?php

namespace Karolina\App;


class User {
    
    public string $username;
    public array $friendshipsList = [];
    public array $blockedUsersList = [];
    public array $posts = [];

    public function __construct($username) {

        $this->username = $username;
    }
    
    public function getFriendships() {

        return $this->friendshipsList;
    }

    public function addFriend(User $friend) {
         
        if ( in_array($friend, $this->blockedUsersList, true) ) {
            throw InvalidFriendRequest::cannotSendFriendRequest();
        }
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

    public function getFriends() {

        $friendshipsList = $this->getFriendships();

        $friends = [];
        foreach($friendshipsList as $friendship) {

            if ($this != $friendship->sender) {
                $friends[] = $friendship->sender;
            } else {
                $friends[] = $friendship->receiver;
            }
        }
        return $friends;
    }
    
    public function removeFriend(User $friend) {

        $friendsList = $this->getFriends();
        $index = array_search($friend,$friendsList);
        unset($this->friendshipsList[$index]);

        $removedUsersFriendsList = $friend->getFriends();
        $index = array_search($this,$removedUsersFriendsList);
        unset($friend->friendshipsList[$index]);
    }


    public function blockUser(User $user) {
        
        if ( ! in_array($user, $this->blockedUsersList, true) ) {
            $this->blockedUsersList[] = $user;
        } else {
            echo "You have already blocked this user";
        }
    }

    public function createPost(string $text) {

        $this->posts[] = $text;
    }

    public function requestToViewPosts(User $user) {

        $friends = $this->getFriends();
        if ( $user==$this ) {
            return $this->posts;

        } elseif (! in_array($user, $friends, true) ) {
            return [];
            
        } else {
            return $user->posts;
        }
    }


}