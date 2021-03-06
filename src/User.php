<?php

namespace Karolina\App;

class User
{
    
    public string $username;
    public array $friendshipsList = [];
    public array $blockedUsersList = [];
    public array $posts = [];
    public array $likedPosts = [];
    public array $dislikedPosts = [];

    public function __construct($username) 
    {
        $this->username = $username;
    }
    
    public function getFriendships() 
    {
        return $this->friendshipsList;
    }

    /**
     * @throws InvalidFriendRequest
     */
    public function addFriend(User $friend)
    {
        if (in_array($this, $friend->blockedUsersList, true)) {
            throw new InvalidFriendRequest('Cannot send a friend request to this user.');
        }

        $commonFriendship = new Friendship($this, $friend);
        $this->friendshipsList[] = $commonFriendship;
        $friend->friendshipsList[] = $commonFriendship;
    }

    public function getFriendshipRequests()
    {
        $friendshipRequests = [];
        foreach ($this->friendshipsList as $friendship) {
            if ($friendship->status == "pending") {

                $friendshipRequests[] = $friendship;
            }
        }
        return $friendshipRequests;
    }

    public function acceptFriendshipRequest(User $sender) 
    {

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

    public function getFriends(): array
    {
        $acceptedFriendships = array_filter(
            $this->getFriendships(),
            fn(Friendship $friendship) => $friendship->isAccepted()
        );

        $friends = array_map(
            fn(Friendship $friendship) => $friendship->getFriendOf($this),
            $acceptedFriendships
        );

        return array_values($friends);
    }

    public function removeFriend(User $friend)
    {
        $friendsList = $this->getFriends();
        $index = array_search($friend,$friendsList);
        unset($this->friendshipsList[$index]);

        $removedUsersFriendsList = $friend->getFriends();
        $index = array_search($this,$removedUsersFriendsList);
        unset($friend->friendshipsList[$index]);
    }

    public function blockUser(User $user)
    {
        if (! in_array($user, $this->blockedUsersList, true) ) {
            $this->blockedUsersList[] = $user;
        } else {
            echo "You have already blocked this user";
        }
    }
}