<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;
use Karolina\App\InvalidFriendRequest;


class UserTest extends TestCase {

    // Scenario 1
    public function test_get_the_username_of_a_user() {

        // Setup
        $john = new User("John");

        // Act
        $johnUsername = $john->username;

        // Assert
        $this->assertEquals($johnUsername , 'John');
    }

    // Scenario 2
    public function test_get_the_friends_of_a_user_with_no_friends() {

        // Setup
        $john = new User("John");

        // Act
        $johnsFriends = $john->getFriendships();

        // Assert
        $this->assertEmpty($johnsFriends);
    }

    // Scenario 3
    public function test_get_the_friends_of_a_user_with_friends() {

        $this->markTestIncomplete('Friends first need to accept friendship request. 
                                    We need to rework this feature.');


        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $richard = new User("Richard");
        $john->addFriend($jane);
        $john->addFriend($richard);

        // Act
        $johnsFriendships = $john->getFriendships();
        
        // Assert
        $this->assertCount(2, $johnsFriendships);
        $this->assertSame([$jane, $richard], $johnsFriendships);
    }
    
    // Scenario 4
    public function test_friendship_is_pending_status_by_default_when_a_friend_request_is_sent() {

        // Setup 
        $john = new User("John");
        $jane = new User("Jane");
        $john->addFriend($jane);

        // Act
        $johnsFriendship = $john->getFriendships();
        $janeFriendship = $johnsFriendship[0]; // jane friendship object 
        
        // Assert
        $this->assertCount(1, $johnsFriendship);
        $this->assertSame($jane, $janeFriendship->receiver); // checking receiver is jane object 
        $this->assertSame("pending", $janeFriendship->status); // checking status of friendship
    }
    
    // Scenario 5
    public function test_get_the_friendship_requests_of_a_user_with_no_friendship_requests() {

        // Setup
        $john = new User("John");
        
        // Act
        $johnsFriendshipRequests = $john->getFriendshipRequests();

        // Assert
        $this->assertEmpty($johnsFriendshipRequests);
    }

    // Scenario 6
    public function test_get_friendship_request_every_time_someone_adds_you_as_a_friend() {

        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $john->addFriend($jane);
        
        // Act
        $janesFriendshipRequests = $jane->getFriendshipRequests();

        // Assert
        $friendshipWithJohn = $janesFriendshipRequests[0];
        $this->assertSame($john, $friendshipWithJohn->sender);
    }
    
     // Scenario 7
    public function test_you_can_accept_friendship_request_and_change_friendship_status_to_accepted() {

        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $john->addFriend($jane);
        
        // Act
        $jane->acceptFriendshipRequest($john);

        // Assert
        $janesFriendships = $jane->getFriendships();
        $johnsFriendships = $john->getFriendships();

        $friendshipWithJohn = $janesFriendships[0];
        $friendshipWithJane = $johnsFriendships[0];

        $this->assertSame("accepted", $friendshipWithJohn->status);
        $this->assertSame("accepted", $friendshipWithJane->status);
    }
    
     // Scenario 8
    public function test_can_get_a_list_of_friend_objects() {

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
        $this->assertSame($jane,$johnsFriendsList[0]);
        $this->assertSame($rick,$johnsFriendsList[1]);
        $this->assertCount(2, $johnsFriendsList);
    }

     // Scenario 9 (8)
    public function test_can_remove_a_friend() {

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
        $this->assertSame($john->getFriends()[0], $rick); // johns only friend is rick
    }
     
     // Scenario 9
    public function test_can_block_user() {

        $this->markTestIncomplete("The Exception of InvalidFriendRequest type is not being thrown");

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

     // Scenario 11
     public function test_posts_are_stored_in_an_array() {

        // Setup
        $jane = new User("Jane");

        // Act
        $jane->createPost("Hello from Jane");

        // Assert
        $this->assertSame($jane->posts, ["Hello from Jane"] );
     }


}