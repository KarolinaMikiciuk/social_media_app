<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;
use Karolina\App\InvalidFriendRequest;
use Karolina\App\ManagePosts;


class ManagePostsTest extends TestCase {

     // Scenario 10

     // Scenario 10.1
     public function test_posts_are_stored_in_an_array()
     {

        // Setup
        $jane = new User("Jane");
        $postsManager = new ManagePosts();

        // Act
        $postsManager->createPost($jane, "Hello from Jane");

        // Assert
        $this->assertSame($jane->posts, ["Hello from Jane"] );
     }
    
    // Scenario 10.2
    public function test_can_return_own_posts() 
    {

        // Setup
        $jane = new User("Jane");
        $postsManager = new ManagePosts();
        $postsManager->createPost($jane, "Hello from Jane");

        // Act
        $viewedPosts = $postsManager->requestToViewPosts($jane, $jane);

        // Assert
        $this->assertSame($viewedPosts, ["Hello from Jane"] );
    }
    
     // Scenario 10.3
    public function test_can_view_only_the_posts_made_by_the_friends_of_the_user() 
    {

        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $rick = new User("Rick");
        $john->addFriend($jane);
        $jane->acceptFriendshipRequest($john);

        $postsManager = new ManagePosts();

        $postsManager->createPost($rick, "Hello from Rick");
        $postsManager->createPost($jane, "Hello from Jane");

        // Act
        $postsRequestedFromJane = $postsManager->requestToViewPosts($john, $jane);
        $postsRequestedFromRick = $postsManager->requestToViewPosts($john, $rick);

        // Assert
        $this->assertSame($postsRequestedFromJane, ["Hello from Jane"]);
        $this->assertSame($postsRequestedFromRick, []);
    }


}