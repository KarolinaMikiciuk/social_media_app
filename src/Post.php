<?php

namespace Karolina\App;


class Post {

    public User $author;
    public string $text;
    public int $likes;
    public int $dislikes;
    
    public function __construct(User $author, string $text)
    {
        $this->author = $author;
        $this->text = $text;
        $this->likes = 0;
        $this->dislikes = 0;
    }

    public function isNotSpecifiedPost(Post $post)
    {
        if ( $this !== $post) {
            return $this;
        }
    }
    
    public function getRemainingPosts(User $personRemovingPost, array $postsArray)
    {
        $remainingPosts = array_filter(
                    $postsArray,
                    fn(Post $genericPost) => $genericPost->isNotSpecifiedPost($this)
                );
        return $remainingPosts;
    }
}