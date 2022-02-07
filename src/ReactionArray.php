<?php

namespace Karolina\App;


class ReactionArray {
    // can perhaps reimplement as interface with dislike n like classes?
    public bool $isLikesArray;
    public array $postsArray;

    
    public function __construct(bool $isLikesArray, array $postsArray)
    {
        $this->isLikesArray = $isLikesArray;
        $this->postsArray = $postsArray;
    }

    public function hasUserReactedToThisPost(Post $post)
    {
        return in_array($post, $this->postsArray, true);
    }
    
    public function enforceIdempotency(User $personReactingToPost, Post $post)
    { 
        $remainingPosts = $post->getRemainingPosts($personReactingToPost, $this->postsArray);

        if ($this->isLikesArray) {
            $post->likes -= 1;
            $personReactingToPost->likedPosts = array_values($remainingPosts); 
        } else{
            $post->dislikes -= 1;
            $personReactingToPost->dislikedPosts = array_values($remainingPosts); 
        }
    }

    public function appendReaction(User $personReactingToPost, Post $post)
    {
        if ($this->isLikesArray) {
            $post->likes ++;
            $personReactingToPost->likedPosts[] = $post;

        } else {
            $post->dislikes ++;
            $personReactingToPost->dislikedPosts[] = $post;
        }
    }

    public function reactToPost(User $personReactingToPost, Post $post)
    {

        $friendsOfUser = $personReactingToPost->getFriends();

        if ( $this->hasUserReactedToThisPost($post) ) {
            $this->enforceIdempotency($personReactingToPost, $post);

        } else {
            ( in_array($post->author, $friendsOfUser, true) ) 
            ? $this->appendReaction($personReactingToPost, $post)
            : throw new InvalidPostReaction("You cannot dislike/like a post of a user who is not your friend");
        }
    }

}