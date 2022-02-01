<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;
use Karolina\App\InvalidFriendRequest;


class UserTest extends TestCase {

    // Scenario 1
    public function test_get_the_username_of_a_user() 
    {

        // Setup
        $john = new User("John");

        // Act
        $johnUsername = $john->username;

        // Assert
        $this->assertSame($johnUsername , 'John');
    }

    // Scenario 2
    public function test_get_the_friends_of_a_user_with_no_friends()
    {
        // Setup
        $john = new User("John");

        // Act
        $johnsFriends = $john->getFriendships();

        // Assert
        $this->assertEmpty($johnsFriends);
    }

    // Scenario 3
    public function test_get_the_friends_of_a_user_with_friends()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $richard = new User("Richard");

        $john->addFriend($jane);
        $john->addFriend($richard);

        $richard->acceptFriendshipRequest($john);
        $jane->acceptFriendshipRequest($john);

        // Act
        $johnsFriendships = $john->getFriends();

        // Assert
        $this->assertCount(2, $johnsFriendships);
        $this->assertSame([$jane, $richard], $johnsFriendships);
    }

    public function test_can_get_a_list_of_friends()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $rick = new User("Rick");

        $john->addFriend($jane);
        $jane->acceptFriendshipRequest($john);

        $john->addFriend($rick);
        $rick->acceptFriendshipRequest($john);

        // Act
        $johnsFriendsList = $john->getFriends();

        // Assert
        $this->assertSame($jane, $johnsFriendsList[0]);
        $this->assertSame($rick, $johnsFriendsList[1]);
        $this->assertCount(2, $johnsFriendsList);
    }
    
    // Scenario 4
    public function test_friendship_is_pending_status_by_default_when_a_friend_request_is_sent()
    {
        // Setup 
        $john = new User("John");
        $jane = new User("Jane");

        $john->addFriend($jane);

        // Act
        $johnsFriendships = $john->getFriendships();
        $janeFriendship = $johnsFriendships[0]; // jane friendship object
        
        // Assert
        $this->assertCount(1, $johnsFriendships);
        $this->assertSame($jane, $janeFriendship->receiver); // checking receiver is jane object 
        $this->assertSame("pending", $janeFriendship->status); // checking status of friendship
    }
    
    // Scenario 5
    public function test_get_the_friendship_requests_of_a_user_with_no_friendship_requests()
    {
        // Setup
        $john = new User("John");
        
        // Act
        $johnsFriendshipRequests = $john->getFriendshipRequests();

        // Assert
        $this->assertEmpty($johnsFriendshipRequests);
    }

    // Scenario 6
    public function test_get_friendship_request_every_time_someone_adds_you_as_a_friend()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");

        // Sanity check (initial state before acting)
        $this->assertEmpty($jane->getFriendshipRequests());

        // Act
        $john->addFriend($jane);

        // Assert (the status of the system has changed)
        $janesFriendshipRequests = $jane->getFriendshipRequests();

        $this->assertCount(1, $janesFriendshipRequests, 'Jane has no friendship requests');

        $friendshipWithJohn = $janesFriendshipRequests[0];
        $this->assertSame($john, $friendshipWithJohn->sender);
        $this->assertSame($jane, $friendshipWithJohn->receiver);
        $this->assertSame('pending', $friendshipWithJohn->status);
    }
    
     // Scenario 7
    public function test_you_can_accept_friendship_request_and_change_friendship_status_to_accepted()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $john->addFriend($jane);

        // Act
        $jane->acceptFriendshipRequest($john);

        // Assert
        $janesFriendships = $jane->getFriendships();
        $johnsFriendships = $john->getFriendships();

        $this->assertCount(1, $johnsFriendships, 'John has no friendship requests');
        $this->assertCount(1, $janesFriendships, 'Jane has no friendship requests');

        $this->assertSame($janesFriendships[0], $johnsFriendships[0]);

        $this->assertSame("accepted", $janesFriendships[0]->status);
        $this->assertSame("accepted", $johnsFriendships[0]->status);
    }

     // Scenario 8
    public function test_can_remove_a_friend()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $rick = new User("Rick");

        $john->addFriend($jane);
        $john->addFriend($rick);
        $jane->acceptFriendshipRequest($john);
        $rick->acceptFriendshipRequest($john);

        // Act
        $jane->removeFriend($john);

        // Assert
        $this->assertEmpty($jane->getFriends()); // jane has no friends
        $this->assertCount(1, $john->getFriends());
        $this->assertSame($john->getFriends()[0], $rick); // johns only friend is rick
    }
     
     // Scenario 9
    public function test_can_block_user()
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");

        // Act
        $jane->blockUser($john);

        // Assert
        $this->expectException(InvalidFriendRequest::class);
        $john->addFriend($jane);
    }

     // Scenario 10

     // Scenario 10.1
     public function test_posts_are_stored_in_an_array()
     {

        // Setup
        $jane = new User("Jane");

        // Act
        $jane->createPost("Hello from Jane");

        // Assert
        $this->assertSame($jane->posts, ["Hello from Jane"] );
     }
    
    // Scenario 10.2
    public function test_can_return_own_posts() 
    {

        // Setup
        $jane = new User("Jane");
        $jane->createPost("Hello from Jane");

        // Act
        $viewedPosts = $jane->requestToViewPosts($jane);

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
        $jane->createPost("Hello from Jane");
        $rick->createPost("Hello from Rick");

        // Act
        $postsRequestedFromJane = $john->requestToViewPosts($jane);
        $postsRequestedFromRick = $john->requestToViewPosts($rick);

        // Assert
        $this->assertSame($postsRequestedFromJane, ["Hello from Jane"]);
        $this->assertSame($postsRequestedFromRick, []);
    }

     // Scenario 12
    public function test_cannot_block_the_same_user_twice() 
    {


    }

}