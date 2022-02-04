<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;
use Karolina\App\InvalidFriendRequest;
use Karolina\App\ManagePosts;
use Karolina\App\Post;
use Karolina\App\InvalidPostLiking;




class ManagePostsTest extends TestCase {

     // Scenario 10

     // Scenario 10.1
     public function test_posts_are_stored_in_an_array()
     {
        // Setup
        $jane = new User("Jane");
        $postsManager = new ManagePosts();

        // Act
        $janesPost = $postsManager->createPost($jane, "Hello from Jane");

        // Assert
        $this->assertCount(1, $jane->posts); 
        //$janesPost = new Post($jane, "Hello from Jane"); <-- legacy
        $this->assertSame($jane->posts, [$janesPost]); 
     }

    // Scenario 10.2
    public function test_can_return_own_posts() 
    {
        // Setup
        $jane = new User("Jane");
        $postsManager = new ManagePosts();
        $createdPost = $postsManager->createPost($jane, "Hello from Jane");

        // Act
        $viewedPosts = $postsManager->requestToViewPosts($jane, $jane);

        // Assert
        $this->assertSame($viewedPosts, [$createdPost] );
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
        $janesPost = $postsManager->createPost($jane, "Hello from Jane");

        // Act
        $postsRequestedFromJane = $postsManager->requestToViewPosts($john, $jane);
        $postsRequestedFromRick = $postsManager->requestToViewPosts($john, $rick);

        // Assert
        $this->assertSame($postsRequestedFromJane, [$janesPost]);
        $this->assertSame($postsRequestedFromRick, []);
    }
     
     // Scenario 13
    public function test_can_like_a_post_of_a_friend()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");

        $postsManager = new ManagePosts();

        $post1 = $postsManager->createPost($jane, "Hello from Jane");
        $post2 = $postsManager->createPost($jane, "I am hungry");

        $john->addFriend($jane);
        $jane->acceptFriendshipRequest($john);

        // Act
        $postsManager->likePost($john, $post2);

        // Assert
        $this->assertSame(1, $post2->likes);
    }

     // Scenario 14
    public function test_a_liked_post_gets_appended_to_the_liked_posts_list_of_a_user()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");

        $postsManager = new ManagePosts();

        $post1 = $postsManager->createPost($jane, "Hello from Jane");
        $post2 = $postsManager->createPost($jane, "I am hungry");

        $john->addFriend($jane);
        $jane->acceptFriendshipRequest($john);

        $postsManager->likePost($john, $post2);

        // Act
        $johnsLikedPosts = $john->likedPosts;

        // Assert
        $this->assertSame($johnsLikedPosts, [$post2]);
    }

     // Scenario 15
    public function test_throws_an_exception_when_you_like_a_post_of_a_non_friend()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");

        $postsManager = new ManagePosts();

        $post1 = $postsManager->createPost($jane, "Hello from Jane");
        $post2 = $postsManager->createPost($jane, "I am hungry");

        // Assert- Expect
        $this->expectException(InvalidPostLiking::class);

        // Act 
        $postsManager->likePost($john, $post2);
    }

     // Scenario 16
    public function test_cannot_like_the_same_post_twice()
    {
        
    }
}