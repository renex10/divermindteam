<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'users' => 150,
                'orders' => 89,
                'revenue' => 12500
            ],
            'recent_activities' => $this->getRecentActivities()
        ]);
    }
    
    public function analytics()
    {
        return Inertia::render('Analytics', [
            'data' => $this->getAnalyticsData()
        ]);
    }
    
    private function getRecentActivities()
    {
        // Tu lógica aquí
        return [];
    }
    
    private function getAnalyticsData()
    {
        // Tu lógica aquí
        return [];
    }
}