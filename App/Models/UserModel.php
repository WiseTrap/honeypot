<?php

namespace WiseTrap\App\Models;

use WiseTrap\Core\Application;

class UserModel extends DbModel
{
    public int $UserId;
    public int $GroupId;
    static function tableName(): string
    {
        return 'Users';
    }
    public function attributes(): array
    {
        return ['Username', 'Password'];
    }
    public function rules(): array
    {
        return [
            'username' => [Validator::RULE_REQUIRED, Validator::RULE_SIMPLE, [Validator::RULE_MIN, 'min' => 3], [Validator::RULE_MAX, 'max' => 20]],
            'password' => [Validator::RULE_REQUIRED, Validator::RULE_MIX, [Validator::RULE_MAX, 'max' => 24]],
        ];
    }
    public function login(): bool
    {
        $this->username = trim(htmlspecialchars($this->username));
        $this->password = sha1($this->password);
        $user = $this->findOne(['username' => $this->username]);
        if (!$user) {
            $this->addError('username', 'User not found');
            return false;
        }
        if ($this->password != $user->Password) {
            $this->addError('password', 'Error Password');
            return false;
        }
        return Application::$app->login($user);
    }
    public static function primaryKey(): string
    {
        return 'UserId';
    }
    public function profile()
    {
        return UserProfileModel::findOne(['UserId' => $this->UserId]);
    }
}