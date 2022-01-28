<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;


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
        $friendshipWithJohn = $janesFriendshipRequests[0];

        // Assert
        $this->assertSame($john, $friendshipWithJohn->sender);
    }
    
    




}