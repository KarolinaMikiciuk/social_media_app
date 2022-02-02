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

}