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
        $johnsFriends = $john->getFriends();

        // Assert
        $this->assertEmpty($johnsFriends);
    }

    public function test_get_the_friends_of_a_user_with_friends() {

        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $richard = new User("Richard");
        $john->addFriend($jane);
        $john->addFriend($richard);

        // Act
        $johnsFriends = $john->getFriends();
        
        // Assert
        $this->assertCount(2, $johnsFriends);
        $this->assertSame([$jane, $richard], $johnsFriends);
    }
    

}