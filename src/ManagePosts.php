<?php

namespace Karolina\App;


class ManagePosts {

    
    public function createPost(User $user, string $text) 
    {
        $post = new Post($user, $text);
        $user->posts[] = $post;
        return $post;
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
    
    /**
     * @throws InvalidPostLiking
     */
    public function likePost(User $personLikingPost, $post) // like posts only of your friends
    {
        $friendsOfUser = $personLikingPost->getFriends();
        if ( in_array($post->author, $friendsOfUser, true) ) {
            $post->likes ++;
            $personLikingPost->likedPosts[] = $post;
        } else {
            throw new InvalidPostLiking("You cannot like a post of a user who is not your friend");
        }
    }

}