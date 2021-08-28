<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Series;


use Carbon\Carbon;
/** mail */
use Illuminate\Support\Facades\Mail;
use Config;

class SeriesController extends Controller
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
    public function series()
    {
        return view('series', [
            'series' => $this->findSeries(),
        ]);
    }
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:55'],
                'sections' => ['required', 'string', 'max:3'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            $data = $request->all();
            $data['sections'] = intval($data['sections']);
            if(!$data['sections'])
            {
                throw new \Exception("Number of sections cannot be set to zero");
            }
            Series::create($data);
            return redirect()->back()->with(['suc' => 'Created successfully!']);
        } catch (\Throwable $e) {
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    protected function findSeries()
    {
        return Series::all()->toArray();
    }
   
}
