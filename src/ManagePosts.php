<?php

namespace Karolina\App;


class ManagePosts {

    
    public function createPost(User $user, string $text) 
    {
        $user->posts[] = $text;
    }

    public function requestToViewPosts(User $requester, User $user) 
    {
        $friends = $requester->getFriends();
        if ( $user==$requester ) {
            return $requester->posts;

        } elseif (! in_array($user, $friends, true) ) {
            return [];

        } else {
            return $user->posts;
        }
    }

}