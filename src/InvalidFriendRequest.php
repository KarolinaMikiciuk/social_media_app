<?php

namespace Karolina\App;


class InvalidFriendRequest extends Exception {

    public static function cannotSendFriendRequest()
    {
        return new static('Cannot send a friend request to this user.');
    }
}