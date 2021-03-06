<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Karolina\App\User;
use Karolina\App\Friendship;
use Karolina\App\InvalidFriendRequest;
use Karolina\App\ManagePosts;



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

     // Scenario 12
    public function test_cannot_block_the_same_user_twice() 
    {
        // Setup
        $john = new User("John");
        $jane = new User("Jane");
        $jane->blockUser($john);

        // Sanity check 1: john is in the blocked users list
        $this->assertSame($jane->blockedUsersList, [$john]);

        // Act
        $jane->blockUser($john);
        
        // Sanity check 2: john is in the blocked users list only once
        $this->assertSame($jane->blockedUsersList, [$john]);
        
        // Assert
        $this->expectOutputString("You have already blocked this user");
    }

}