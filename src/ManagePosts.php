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
    
    public function likePost(User $personLikingPost, Post $post) // like posts only of your friends
    {
        $likedPostsArray = new ReactionArray(false, $personReactingToPost->likedPosts);
        $likedPostsArray->reactToPost($personReactingToPost, $post);
    }
    
    public function dislikePost(User $personReactingToPost, Post $post) // dislike posts only of your friends
    {
        $dislikedPostsArray = new ReactionArray(false, $personReactingToPost->dislikedPosts);
        $dislikedPostsArray->reactToPost($personReactingToPost, $post);
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