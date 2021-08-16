<?php

namespace Mohamed179\Core;

use Mohamed179\Core\Database\UserModel;
use App\Models\User;

class Authentication
{
    public ?UserModel $user;

    public function __construct()
    {
        if (Application::$app->session->has('user_id')) {
            $userClass = Application::$app->config['userClass'];
            $userId = Application::$app->session->get('user_id');
            $this->user = $userClass::find($userId);
        }
    }

    public function isGuest(): bool
    {
        return !isset($this->user);
    }

    public function login(UserModel $user): bool
    {
        if (isset($this->user) || is_null($user) || $user->id === 0) {
            return false;
        }

        $this->user = $user;
        Application::$app->session->set('user_id', $user->id);
        return true;
    }

    public function logout(): bool
    {
        $this->user = null;
        Application::$app->session->unset('user_id');
        return true;
    }
}