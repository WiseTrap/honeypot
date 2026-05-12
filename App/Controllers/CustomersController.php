<?php

namespace WiseTrap\App\Controllers;

class CustomersController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'customers');
        return $this->render('customers.index');
    }
}