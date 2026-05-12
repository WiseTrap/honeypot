<?php

namespace WiseTrap\App\Controllers;

use WiseTrap\App\Models\UserModel;
use WiseTrap\Core\Application;

class DashController extends Controller
{
    public function index(): bool|array|string
    {
        $this->setLayoutParam('title', 'Dashboard');
        try {
            $user = Application::$app->user;
            if (!($user instanceof UserModel)) {
                redirect('/auth');
            }

            $userProfile= $user->profile();

            return $this->render('dashboard.index', [
                'userProfile'   => $userProfile,
            ]);
        } catch (\Throwable $e) {
            echo '<pre>Error in ProjectController::index(): '
                . htmlspecialchars($e->getMessage())
                . "\n\nStack trace:\n"
                . htmlspecialchars($e->getTraceAsString())
                . '</pre>';
            exit;
        }
    }
    public function about(): bool|array|string
    {
        $this->setLayoutParam('title', 'About');
        return $this->render('dashboard.about');
    }
    public function contact(): bool|array|string
    {
        $this->setLayoutParam('title', 'Contact');
        return $this->render('dashboard.contact');
    }
    public function profile(): bool|array|string
    {
        $this->setLayoutParam('title', 'Profile');
        return $this->render('dashboard.profile');
    }
    public function settings(): bool|array|string
    {
        $this->setLayoutParam('title', 'Settings');
        return $this->render('dashboard.settings');
    }
}