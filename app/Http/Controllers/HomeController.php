<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Series;
use App\Models\Section;
use App\Models\Question;
use App\Models\Option;
use App\Models\Response;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Progresitem;
use App\Models\CustomerResponse;
use App\Models\Chart;
use App\Models\Corporate;
use App\Models\Invoice;
use App\Models\Price;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Initsection;
use App\Mail\Sectiongraph;
use App\Mail\PaymentReceived;
use Config;
use Storage;

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

    public function adm_ngo()
    {
        return view('adm_ngo', [
            'ngos' => $this->findNgos(),
        ]);
    }
    protected function findNgos()
    {
        $rtn = Corporate::all();
        if(!is_object($rtn)) { return []; }
        return $rtn->toArray();
    }
    public function adm_view_ngo($id)
    {
        return view('adm_view_ngo', [
            'thisItem' => Corporate::find($id),
        ]);
    }
    public function adm_ngo_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
                'name' => ['required', 'string', 'max:55'],
                'phone' => ['required', 'string', 'max:15'],
                'contact_name' => ['required', 'string', 'max:55'],
                'address' => ['required', 'string', 'max:255'],
                'terms' => ['required', 'string', 'max:3'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $data = $request->all();
            $cop = Corporate::find($data['id']);
            $cop->name = $data['name'];
            $cop->phone = $data['phone'];
            $cop->address = $data['address'];
            $cop->phone = $data['phone'];
            $cop->contact_name = $data['contact_name'];
            $cop->terms = abs($data['terms']);
            $cop->save();
            return redirect()->back()->with(['suc' => 'NGO updated successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with( ['mess' => $th->getMessage() ]);
        }
    }
    public function adm_pricing()
    {
        return view('adm_pricing', [
            'pricing' => Price::find(1),
        ]);
    }
    public function adm_price_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:20'],
                'normal' => ['required', 'string', 'max:10'],
                'discounted' => ['required', 'string', 'max:10'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $data = $request->all();
            $price = Price::find(1);
            if( !is_object($price) )
            {
                Price::create($data);
            }
            else
            {
                $price->normal = $data['normal'];
                $price->discounted = $data['discounted'];
                $price->save();
            }
            return redirect()->back()->with(['suc' => 'Price updated successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with( ['mess' => $th->getMessage() ]);
        }
    }




    public function stream($file)
    {
        $filename = ('app/graphs/' . $file);
        return response()->download(storage_path($filename), null, [], null);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adm_account()
    {
        return view('profile', [
            'data' => [],
        ]);
    }
    public function change_pwd(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8'],
                'confirm_password' => ['required', 'string', 'min:8', 'same:password'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return redirect()->back()->with([
                'suc' => 'Update completed successfully!',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([ 'mess' => $th->getMessage() ]);
        }
    }
    public function index()
    {
        if( Auth::user()->user_group == 2 )
        {
            $orgmeta = Corporate::where('email', Auth::user()->email)->first();
            return view('home', [
                'orgmeta' => $orgmeta,
                'orderData' => $this->orgOrders(),
            ]);
        }
        $spec_orders = Customer::where('has_paid', true)->where('is_spec', true)->count() * Price::find(1)->discounted;
        
        $spec_orders = number_format($spec_orders, 2);
        return view('home', [
            'recents' => $this->findRecentSignups(),
            'series' => Series::find(Config::get('app.series')),
            'completed' => Progresitem::where('has_finished', 2)->count(),
            'rev' => number_format((Payment::where('is_paid', true)->sum('amount')), 2),
            'orders' => $spec_orders,
        ]);
    }
    protected function orgOrders()
    {
        $orgmeta = Corporate::where('email', Auth::user()->email)->first();
        $data = Invoice::where('org', $orgmeta->id)->where('is_paid', true)->get();
        if( is_object($data) )
        {
            return $data->toArray();
        }
        return [];
    }
    protected function findRecentSignups()
    {
        $d = Customer::where('is_active', 1)->orderBy('id', 'desc')->take(5)->get();
        if(is_null($d)){ return []; }
        return $d->toArray();
    }
    public function leave()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function graphs()
    {
        return view('graphs', [
            'data' => [],
        ]);
    }
    public function responses()
    {
        return view('responses', [
            'data' => $this->customerRespList(),
        ]);
    }
    public function c_response($cid, $attempt)
    {
        $customer = Customer::find($cid);
        $documentMeta = Chart::where('customer', $cid)->where('attempt', $attempt)->first();
        $thisGraph = $thisReport = null;
        if(is_object($documentMeta))
        {
            $thisGraph = explode('/', $documentMeta->graph)[2];
            $thisReport = explode('/', $documentMeta->full_doc)[2];
        }
        $customer->ref = $this->getUserRef($customer->name, $cid);
        return view('c_response', [
            'data' => $this->findCustomerRespById($cid, $attempt),
            'thisItem' => $customer,
            'hasGraph' => count($this->getUserCompletedSections($cid, $attempt)),
            'thisGraph' => $thisGraph,
            'thisReport' => $thisReport,
        ]);
    }
    protected function attemptByList()
    {
        $d = Progresitem::where('paid', true)->orderBy('customer', 'desc')->get();
        if( !is_object($d) ){ return []; }
        return $d->toArray();
    }
    protected function customerRespList()
    {
        $data = $this->attemptByList();
        $rtn = [];
        foreach ($data as $_data) {
            $customerData = Customer::find($_data['customer']);
            if( is_object($customerData) )
            {
                $customerData['attempt'] = $_data['attempt'];
                $customerData['ref'] = $this->getUserRef($customerData['name'], $customerData['id']);
                $customerData['comp'] = $this->countUserCompletedSections($_data['customer'], $_data['attempt']);
                array_push($rtn, $customerData);
            }
        }
        return $rtn;
    }
    protected function getUserRef($name, $id)
    {
        $nameArray = explode(' ', $name);
        $ref = substr($nameArray[0], 0, 2);
        if(isset($nameArray[1]))
        {
            $ref = $ref .  substr($nameArray[1], 0, 3);
        }
        return strtoupper($ref = $ref . '20210' . $id);
    }
    protected function countUserCompletedSections($customer, $attempt)
    {
        return CustomerResponse::distinct()->where('customer', $customer)
            ->where('attempt', $attempt)
            ->count('section');
    }
    protected function getUserCompletedSections($customer, $attempt)
    {
        $rtn = CustomerResponse::distinct()->where('customer', $customer)->where('attempt', $attempt)->get('section');
        if(!is_object($rtn)){ return []; }
        return $rtn->toArray();
    }
    protected function findCustomerRespById($customer, $attempt)
    {
        $sections = Section::whereIn('id', $this->getUserCompletedSections($customer, $attempt))->orderBy('id', 'asc')->get();
        if(!is_object($sections)) { return []; }
        $sections = $sections->toArray();
        return $this->formatSectionResponses($sections, $customer, $attempt);
    }
    protected function formatSectionResponses($data, $customer, $attempt)
    {
        $rtn = [];
        foreach ($data as $_data) {
            $sectionResponse = CustomerResponse::where('section', $_data['id'])->where('customer', $customer)->where('attempt', $attempt)->orderBy('id', 'asc')->get();
            if(is_object($sectionResponse))
            {
                $_data['total'] = Question::where('section', $_data['id'])->count();
                $_data['responses'] = $sectionResponse->toArray();
                array_push($rtn, $_data);
            }
        }
        return $rtn;
    }
}
