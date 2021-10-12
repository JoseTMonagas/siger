<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userType = Auth::user()->userable;
        $userNotifications = Auth::user()->notifications()->latest('created_at')->take(3)->get();
        if ($userNotifications->count() > 0) {
            $notifications = [];
            foreach ($userNotifications as $notification) {
                $data = $notification->data;
                array_push($notifications, \App\Requerimiento::findOrFail($data['requerimiento_id']));
            }
        } else {
            $notifications = [];
            $data = [];
        }

        if (($userType instanceof \App\Centro) || ($userType instanceof \App\Empresa) || ($userType instanceof \App\Holding)) {
            return view('cliente.home')->with(compact('notifications', 'data'));
        } elseif ($userType instanceof \App\CompassRole) {
            return view('compass.home')->with(compact('notifications'));
        } else {
            return view('login');
        }
    }
}
