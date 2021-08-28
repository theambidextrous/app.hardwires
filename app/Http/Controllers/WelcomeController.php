<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Config;

class WelcomeController extends Controller
{
    
    public function index()
    {
        return view('welcome');
    }
}
