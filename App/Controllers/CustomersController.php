<?php

namespace WiseTrap\App\Controllers;

class CustomersController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'Customers');
        return $this->render('customers.index');
    }
}