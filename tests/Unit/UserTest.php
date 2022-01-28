<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;

class UserTest extends TestCase {


    public function test_get_the_username_of_a_user() {

        // Setup
        $john = new User("John");

        // Act
        $johnUsername = $john->username;

        // Assert
        $this->assertEquals($johnUsername , 'John');
    }

    
    public function test_get_the_friends_of_a_user_with_no_friends() {

        // Setup
        $john = new User("John");

        // Act
        $johnsFriends = $john->getFriendships();

        // Assert
        $this->assertEmpty($johnsFriends);
    }

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
    
    public function test_friendship_is_pending_status_by_default_when_a_friend_request_is_sent() {

        // Setup 
        $john = new User("John");
        $jane = new User("Jane");
        $john->addFriend($jane);
        $janeFriendship = new Friendship($jane, $john);

        // Act
        $johnsFriendship = $john->getFriendships();
        
        // Assert
        $this->assertCount(1, $johnsFriendship);
        $this->assertSame($johnsFriendship, [$janeFriendship]);
        $this->assertEqual($janeFriendship->status , "pending");
    }

}