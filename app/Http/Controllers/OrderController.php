<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Series;
use App\Models\Section;
use App\Models\Question;
use App\Models\Option;
use App\Models\Response;
use App\Models\Customer;
use App\Models\Progresitem;
use App\Models\Payment;
use App\Models\CustomerResponse;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Initsection;
use App\Mail\Sectiongraph;
use App\Mail\PaymentReceived;
use Config;
use Storage;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function spec_orders()
    {
        try {
            return view('spec_orders', [
                'data' => $this->findSpecOrders(),
            ]);
        } catch (\Throwable $th) {
            return response([
                'error' => 'System could not load your assessment. Try again later or contact ' . Config::get('mail.from.address') . ' for assistance',
            ],404);
        }
    }
    protected function findSpecOrders()
    {
        $data = Customer::where('is_spec', true)->get();
        if(is_object($data))
        {
            return $this->formatSpecUsers($data->toArray());
        }
        return [];
    }
    protected function formatSpecUsers($data)
    {
        $rtn = [];
        foreach ($data as $_data) {
            $_data['ref'] = $this->getUserRef($_data['name'], $_data['id']);
            array_push($rtn, $_data);
        }
        return $rtn;
    }
    public function new_order(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'max:55'],
                'phone' => ['required', 'string', 'max:25'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $data = $request->all();
            $data['series'] = Config::get('app.series');
            $data['hash'] = md5($data['email']);
            $data['has_paid'] = true;
            $data['is_spec'] = true;
            /** query */
            DB::beginTransaction();
            $createdCustomer = $this->createOrGetCustomer($data);
            $nextAttemptNumber = $this->findAttemptNumberCorporate($createdCustomer, $data['series']);
            
            $progressPayload = [
                'customer' => $createdCustomer,
                'series' => $data['series'],
                'attempt' => $nextAttemptNumber,
                'paid' => true,
            ];
            Progresitem::create($progressPayload);
            /** mail this user */
            $nextMeta = [1, $data['hash'], $nextAttemptNumber ];
            $sectionMeta = Section::find($nextMeta[0]);
            $mailData = [
                'sectionTitle' => 'Hardwires Assessment - ' . $sectionMeta->name,
                'userRef' => $this->getUserRef($data['name'], $createdCustomer),
                'sectionLink' => route('show_section', [ 'no' => $nextMeta[0], 'hash' => $nextMeta[1], 'attempt' => $nextMeta[2] ]),
            ];
            Mail::to($data['email'])->send(new Initsection(json_decode(json_encode($mailData))));
            DB::commit();
            return redirect()->back()->with([
                'suc' => 'Request completed successfully!',
            ]);

        } catch (\PDOException $th) {
            return redirect()->back()->with([ 'mess' => 'Request failed. ' . $this->formatPdoError($th->getMessage()) ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([ 'mess' => $th->getMessage() ]);
        }
    }
    protected function formatPdoError($string)
    {
        if( substr($string, 0, 8) == 'SQLSTATE' )
        {
            $arr = explode(':', $string);
            $a = $b = $c = '';
            if( isset($arr[0]) ) { $a = $arr[0]; }
            if( isset($arr[1]) ) { $b = $arr[1]; }
            if( isset($arr[2]) ) { $c = $arr[2]; }
            return $b . '. ' . substr($c, 0, 21) . '...';
        }
        return 'Data pass error.';
    }
    protected function createOrGetCustomer($data)
    {
        $customer = Customer::where('email', $data['email'])->first();
        if( is_object($customer) )
        {
            return $customer->id;
        }
        return Customer::create($data)->id;
    }
    protected function findAttemptNumberCorporate($customer, $series)
    {
        $p = Progresitem::where('customer', $customer)
            ->where('series', $series)->orderBy('attempt', 'desc')->first();
        if( !is_object($p) ) { return 1; }
        if( intval($p->has_finished) == 2 )
        {
            return intval($p->attempt) + 1;
        }
        throw new \Exception("Error Processing Request. This person(".Customer::find($customer)->name.") has a pending assessment. They must first finish the assessment before attempting again");
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
}
