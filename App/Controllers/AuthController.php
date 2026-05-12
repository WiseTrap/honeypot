<?php

namespace WiseTrap\App\Controllers;

use WiseTrap\App\Models\UserModel;
use WiseTrap\Core\Application;

class AuthController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'Login');

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['username']) || isset($_GET['password']))) {
            http_response_code(400);
            redirect('/auth');
        }
        $userModel = new UserModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->loadData(request('POST'));
            if ($userModel->validate() && $userModel->login()) {
                redirect('/dashboard');
            }
        }

        return $this->render('auth.index', [
            'user' => $userModel,
            'hiddenNavbar' => true
        ]);
    }
    public function logout(): void
    {
        Application::$app->logout();
    }
}