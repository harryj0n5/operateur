<?php

namespace App\Controllers;

use App\Services\PromotionService;

class PromotionController extends BaseController
{
    protected PromotionService $promotionService;

    public function __construct()
    {
        $this->promotionService = new PromotionService();
    }

    public function index()
    {
        $promotions = $this->promotionService->getAll();
        return view('promotions/index', ['promotions' => $promotions]);
    }
}