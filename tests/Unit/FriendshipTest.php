<?php

namespace Unit;

use Karolina\App\Friendship;
use Karolina\App\User;
use PHPUnit\Framework\TestCase;

class FriendshipTest extends TestCase
{
    /** @test */
    function can_get_the_friend_of_someone()
    {
        // Setup
        $john = new User('John');
        $jane = new User('Jane');
        
        // Act
        $friendship = new Friendship(sender: $jane, receiver: $john);
        
        // Assert
        $this->assertSame($jane, $friendship->getFriendOf($john));
        $this->assertSame($john, $friendship->getFriendOf($jane));
    }

    /** @test */
    function returns_true_when_getting_the_status_of_an_accepted_friendship()
    {
        // Setup
        $john = new User('John');
        $jane = new User('Jane');
        $john->addFriend($jane);
        $jane->acceptFriendshipRequest($john);
        
        // Act
        $janesFriendships = $jane->getFriendships();
        $statusOfFriendshipWithJohn = $janesFriendships[0]->status;

        // Assert
        $this->assertSame("accepted", $statusOfFriendshipWithJohn);
    }
}