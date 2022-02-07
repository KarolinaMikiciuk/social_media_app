<?php

namespace Karolina\App;


class Post {

    public User $author;
    public string $text;
    public int $likes;
    
    public function __construct(User $author, string $text)
    {
        $this->author = $author;
        $this->text = $text;
        $this->likes = 0;
    }

    public function isNotSpecifiedPost(Post $post)
    {
        if ( $this !== $post) {
            return $this;
        }
    }
    
    public function getRemainingPosts(User $personRemovingPost)
    {
        $remainingPosts = array_filter(
                    $personRemovingPost->posts,
                    fn(Post $genericPost) => $genericPost->isNotSpecifiedPost($this)
                );
        return $remainingPosts;
    }
}