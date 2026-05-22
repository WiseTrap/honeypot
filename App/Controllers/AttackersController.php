<?php

namespace WiseTrap\App\Controllers;

use WiseTrap\App\Models\AttackersModel;

class AttackersController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'Attackers');
        $model = new AttackersModel();
        $Attackers = $model->getAllAttackers();
        return $this->render('attackers.index', [
            'Attackers' => $Attackers
        ]);
    }
}