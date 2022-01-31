<?php

namespace Unit;

use Karolina\App\Friendship;
use Karolina\App\User;
use PHPUnit\Framework\TestCase;

class FriendshipTest extends TestCase
{
    /** @test */
    function gets_the_friend_of_someone()
    {
        $john = new User('John');
        $jane = new User('Jane');

        $friendship = new Friendship(sender: $jane, receiver: $john);

        $this->assertSame($jane, $friendship->getFriendOf($john));
        $this->assertSame($john, $friendship->getFriendOf($jane));
    }
}