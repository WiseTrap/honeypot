<?php

namespace WiseTrap\App\Controllers;

class AttackersController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'Attackers');
        return $this->render('attackers.index');
    }
}