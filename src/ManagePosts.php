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
    public function likePost(User $personLikingPost, Post $post) // like posts only of your friends
    {
        $friendsOfUser = $personLikingPost->getFriends();

        if ( in_array($post, $personLikingPost->likedPosts, true) ) {
            $post->likes -= 1;
        } else {
            
            if ( in_array($post->author, $friendsOfUser, true) ) {
                $post->likes ++;
                $personLikingPost->likedPosts[] = $post;
            } else {
                throw new InvalidPostLiking("You cannot like a post of a user who is not your friend");
        }
      }
    }

    /**
     * @throws InvalidPostRemoval
     */
    public function removePost(User $personRemovingPost, Post $post)
    {
        if ($post->author === $personRemovingPost ) {
            
            $remainingPosts = $post->getRemainingPosts($personRemovingPost);
            $personRemovingPost->posts = array_values($remainingPosts);

        } else {
            throw new InvalidPostChange("You cannot remove posts of other users");
        }
    }

    public function updatePost(User $personUpdatingPost, Post $post, string $newText)
    {
        if ($post->author === $personUpdatingPost ) {

            $index = array_search($post, $personUpdatingPost->posts);
            $personUpdatingPost->posts[$index]->text = $newText;

        } else {
            throw new InvalidPostChange("You cannot update posts of other users");
        }
    }

}