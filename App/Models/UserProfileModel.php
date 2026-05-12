<?php

namespace WiseTrap\App\Models;

class UserProfileModel extends UserModel
{
    public static function tableName(): string
    {
        return 'Users_Profiles';
    }
}