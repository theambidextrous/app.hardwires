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
use App\Models\Chart;
use App\Models\Corporate;
use App\Models\Invoice;
use App\Models\Price;

/** PDF merger */
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewInvoice;
use App\Mail\Initsection;
use Config;
use Storage;
use PDF;
use File;

class CorporateController extends Controller
{
    public function distribute($order)
    {
        $orgmeta = Corporate::where('email', Auth::user()->email)->first();
        $orderMeta = Invoice::find($order);
        $persons = $this->findSpecOrderPersons($order);
        return view('c_distribute', [
            'orgmeta' => $orgmeta,
            'thisItem' => $orderMeta,
            'used' => count($persons),
            'persons' => $persons,
        ]);
    }
    public function distribute_new(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'max:55'],
                'phone' => ['required', 'string', 'max:25'],
                'org_invoice' => ['required', 'string', 'max:25'],
                'max_persons' => ['required', 'string', 'max:25'],
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
            if( $this->orderMaxPersons($data['org_invoice']) ==  $data['max_persons'] )
            {
                return redirect()->back()->with([ 'mess' => 'Order exhausted! You cannot add any more persons.' ]);
            }
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
                'suc' => 'Success! An email containing a link to the first questionnaire has been sent',
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with([ 'mess' => $th->getMessage() ]);
        }
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
    protected function createOrGetCustomer($data)
    {
        $customer = Customer::where('email', $data['email'])->first();
        if( is_object($customer) )
        {
            return $customer->id;
        }
        return Customer::create($data)->id;
    }
    protected function findCurrentAttempt($customer, $series)
    {
        $p = Progresitem::where('customer', $customer)
            ->where('series', $series)->orderBy('attempt', 'desc')->first();
        if( is_object($p) )
        {
            return intval($p->attempt);
        }
        throw new \Exception("Error Processing Request. Invalid request to start hardwires assessment");
    }
    public function new_c_order(Request $request)
    {
        $unit_cost = 0;
        $due_date = date('m/d/Y');
        try {
            $validator = Validator::make($request->all(), [
                'Quantity' => ['required', 'string'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            DB::beginTransaction();
            $data = $request->all();
            $orgmeta = Corporate::where('email', Auth::user()->email)->first();
            if( $orgmeta->is_member == 'YES' )
            {
                $unit_cost = doubleval(Price::find(1)->discounted);
                $due_date = $this->dateAddDays(intval($orgmeta->terms));
            }elseif( $orgmeta->is_member == 'NO' )
            {
                if( intval($data['Quantity']) >= 20 )
                { $unit_cost = doubleval(Price::find(1)->discounted);
                }else{ $unit_cost = doubleval(Price::find(1)->normal);}
            }
            $cost = $unit_cost * $data['Quantity'];
            $invoicePayload = [
                'org' => $orgmeta->id,
                'item' => 'Hardwires Questionnaire',
                'qty' => $data['Quantity'],
                'unit_cost' => $unit_cost,
                'cost' => $cost,
                'due_date' => $due_date,
            ];
            $invoiceNumber = Invoice::create($invoicePayload)->id;
            $pdf_data = [
                'org' => $orgmeta->name,
                'org_phone' => $orgmeta->phone,
                'org_email' => $orgmeta->email,
                'address' => $orgmeta->address,
                'invoice' => $invoiceNumber,
                'qty' => $data['Quantity'],
                'cost' => number_format(($cost), 2),
                'due_date' => $due_date,
            ];
            $invoicepath = $this->generate_invoice($pdf_data);
            $mailData = [
                'subjectLine' => 'New Invoice - Hardwires Assessments',
                'action' => 'New',
                'name' => $orgmeta->name,
                'invoiceAttachment' => $invoicepath,
                'invoiceNo' => $invoiceNumber,
                'due_date' => $due_date,
            ];
            if( $orgmeta->is_member == 'YES' )
            {
                /** mark as paid, commit, return */
                Invoice::find($invoiceNumber)->update(['is_paid' => 1, 'path' => $invoicepath]);
                DB::commit();
                /** send email */
                Mail::to([Auth::user()->email, 'discover@hardwires.co.za'])->send(new NewInvoice(json_decode(json_encode($mailData))));

                return redirect()->back()->with(['suc' => 'Order placed successfully. Note that your invoice is due on ' . $due_date]);
            }
            elseif( $orgmeta->is_member == 'NO' )
            {
                /** initiate payment, commit, redirect */
                $paymentData = [
                    'email' => $orgmeta->email,
                    'invoice_no' => $invoiceNumber,
                    'amount' => $cost,
                ];
                $apiReturnedPayload = $this->init_paygate($paymentData);
                $paygateResponse = $apiReturnedPayload[0];
                $initPayData = [
                    'customer' => $orgmeta->id,
                    'amount' => $cost,
                    'currency' => 'ZAR',
                    'ref' => $paymentData['invoice_no'],
                    'ext_ref' => $paygateResponse['uuid'],
                    'email' => $orgmeta->email,
                    'req_payload' => json_encode($apiReturnedPayload[1]),
                    'init_payload' => json_encode($paygateResponse),
                ];
                Payment::create($initPayData);
                Invoice::find($invoiceNumber)->update(['path' => $invoicepath]);
                DB::commit();
                /** send email */
                Mail::to([Auth::user()->email, 'discover@hardwires.co.za'])->send(new NewInvoice(json_decode(json_encode($mailData))));

                return redirect()->route('show_iframe', [
                    'pid' => $paygateResponse['uuid'],
                    'hash' => $paymentData['invoice_no'],
                ]);
            }
            else
            {
                throw new \Exception("Error occured. You have NO permissions to make an order");
            }

        } catch (\PDOException $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => 'Data integrity error. Get in touch for more info' . $e->getMessage()]);
        }
        catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage()]);
        }
    }
            

    public function signup()
    {
        return view('co_signup');
    }
    public function new_corporate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:55'],
                'phone' => ['required', 'string', 'max:15'],
                'contact_name' => ['required', 'string', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:15'],
                'is_member' => ['required', 'string', 'max:3', 'not_in:nn'],
                'password' => ['required', 'string', 'min:8', 'max:20'],
                'c_password' => ['required', 'same:password'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $data = $request->all();

            if( Corporate::where('email', $data['email'])->count() )
            {
                throw new \Exception("Email address you entered already exists. Please go to login or reset password.");
            }
            $userData = [
                'name' => $data['contact_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_group' => 2,
            ];
            DB::beginTransaction();
            Corporate::create($data);
            User::create($userData);
            DB::commit();
            return redirect()->route('login')->with(['suc' => 'Account created. Please login with the password you set']);
        } catch (\PDOException $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => 'Data integrity error. Get in touch for more info' . $e->getMessage()]);
        }
        catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage()]);
        }
    }
    protected function dateAddDays($days = 30)
    {
        $date = new \DateTime('now'); 
        $date->add(new \DateInterval('P' . $days . 'D'));
        return $date->format('Y-m-d');
    }
    protected function generate_invoice($pdf_data)
    {
        $uuid_string = (string)Str::uuid() . '.pdf';
        $filename = ('app/graphs/' . $uuid_string);
        PDF::loadView('mails.invoice', ['data' => $pdf_data ])->save(storage_path($filename));
        return $filename;
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
    protected function specOrderCustomerIds($order)
    {
        $d = Customer::select('id')->where('is_spec', 1)->where('org_invoice', $order)->get();
        if(is_object($d)) { return $d->toArray(); }
        return [];
    }
    protected function orderMaxPersons($order)
    {
        $ids = $this->specOrderCustomerIds($order);
        $attemptEntries = Progresitem::whereIn('customer', $ids)->count();
        return $attemptEntries;
    }
    protected function findSpecOrderPersons($order)
    {
        $ids = $this->specOrderCustomerIds($order);
        $attemptEntries = Progresitem::whereIn('customer', $ids)->get();
        if( !is_object($attemptEntries) ) { return []; }
        $attemptEntries = $attemptEntries->toArray();
        return $this->formatSpecPersons($attemptEntries);
    }
    protected function formatSpecPersons($data)
    {
        $rtn = [];
        foreach ($data as $_data) {
            $customerData = Customer::find($_data['customer']);
            $customerData['attempt'] = $_data['attempt'];
            $customerData['ref'] = $this->getUserRef($customerData['name'], $customerData['id']);
            array_push($rtn, $customerData);
        }
        return $rtn;
    }
    protected function init_paygate($data)
    {
        $curl = curl_init();
        $requestBody = $this->reqBody($data);
        curl_setopt_array($curl, [
            CURLOPT_URL => Config::get('app.pg_init_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $requestBody,
        ]);
        $response = $this->urlEncodedToArray(curl_exec($curl));
        $cinfo = curl_getinfo($curl);
        curl_close($curl);
        if ( isset($response['uuid']) ) {
            return [$response, $requestBody];
        }
        else {
            throw new \Exception("Error Initiating Payment. Kindly try again later");

            // throw new \Exception("Error Initiating Payment. Kindly try again later " . json_encode($cinfo) . json_encode($requestBody));
        }
    }
    protected function reqBody($data)
    {
        date_default_timezone_set('Africa/Johannesburg');
        $dateTime = new \DateTime();
        $reqBody = [
            'merchant_id' => Config::get('app.pg_id'),
            'merchant_key' => Config::get('app.pg_secret'),
            'return_url' => route('thank_you'),
            'cancel_url' => route('p_cancel'),
            'notify_url' => route('callback'),
            'email_address' => $data['email'],
            'm_payment_id' => $data['invoice_no'],
            'amount' => $data['amount'],
            'item_name' => 'Corporate HardWires Package Purchase',
        ];
        $initialString = http_build_query($reqBody) . '&passphrase=' . Config::get('app.pg_phrase');
        $signature = md5($initialString);
        $reqBody['signature'] = $signature;
        $fieldsString = http_build_query($reqBody);
        return $fieldsString;
    }
    protected function urlEncodedToArray($str)
    {
        return json_decode($str, true);
        
        $array = explode('&', $str);
        $rtn = [];
        foreach ($array as $item) {
            if(strlen($item))
            {
                $itemArray = explode('=', $item);
                $rtn[$itemArray[0]] = $itemArray[1];
            }
        }
        return $rtn;
    }
}
