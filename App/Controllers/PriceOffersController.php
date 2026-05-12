<?php

namespace WiseTrap\App\Controllers;

class PriceOffersController extends Controller
{
    public function index(): string|array|bool
    {
        $this->setLayoutParam('title', 'Price Offers');
        return $this->render('price_offers.index');
    }
}