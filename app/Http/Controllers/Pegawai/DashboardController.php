<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\DashboardManager;

class DashboardController extends Controller
{
    protected $dashboardManager;
    
    public function __construct(DashboardManager $dashboardManager)
    {
        $this->dashboardManager = $dashboardManager;
    }
    
    public function index()
    {
        $user = Auth::user();
        $data = $this->dashboardManager->resolve($user);
        $view = $this->dashboardManager->resolveView($user);
        
        return Inertia::render($view, $data);
    }
}
