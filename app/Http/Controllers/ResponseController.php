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
// use App\Models\Response;
use App\Models\Customer;
use App\Models\Progresitem;
use App\Models\Payment;
use App\Models\CustomerResponse;
use App\Models\Chart;
use App\Models\Price;
use App\Models\Invoice;
use App\Models\Corporate;
use App\Models\EmailVerification;

/** PDF merger */
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Nextsection;
use App\Mail\Sectiongraph;
use App\Mail\PaymentReceived;
use App\Mail\Verification;
use Config;
use Storage;
use PDF;
use File;

class ResponseController extends Controller
{
    protected function codeVerified($email, $code){
        return EmailVerification::where('email', $email)->where('code', $code)->where('used', false)->count() > 0;
    }
    public function init_customer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'max:55'],
                'phone' => ['required', 'string', 'max:25'],
                'series' => ['required', 'string', 'max:15'],
            ]);
            if($validator->fails()) {
                return response([ 'error' => true, 'message' => 'Name, email and phone fields are required'], 200);
            }
            Session::put('postData', $request->all());
            Session::save();
            $post = Session::get('postData');
            $code = Str::random(6);
            $verification = [
                'name' => $post['name'],
                'email' => $post['email'],
                'code' => $code,
                'used' => false,
            ];
            EmailVerification::where('email', $post['email'])->update(['used' => true]);
            EmailVerification::create($verification);
            Mail::to($post['email'])->send(new Verification(json_decode(json_encode($verification))) );
            return response(
                [ 'success' => true, 'message' => 'Enter the verification code send to you at ' . $post['email']], 
                200
            );
        }
        catch (\Throwable $th) {
            return response([ 'error' => true, 'message' => $th->getMessage() ], 200);
        }
    }
    public function verifyEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => ['required', 'string'],
            ]);
            if($validator->fails()) { return response([ 'error' => true, 'message' => 'Code field is required'], 200); }
            $post = Session::get('postData');
            if( $this->codeVerified($post['email'], $request->get('code')) )
            {
                return response([ 
                    'success' => true, 
                    'message' => 'Code verified'
                    ],200
                );
            }
            return response([ 
                'error' => true, 
                'message' => 'Verification failed. Invalid code'
                ],200
            );
        } catch (\Throwable $th) {
            return response([ 'error' => true, 'message' => $th->getMessage(). ' ==>  ' . json_encode(Session::get('postData')) ], 200);
        }
    }
    public function create_customer()
    {
        try {
            $data = Session::get('postData');
            if(is_null(Series::find($data['series'])))
            {
                throw new \Exception("Questionnaire not found. Contact us for more information");
            }
            $data['hash'] = md5($data['email']);
            if( $this->isActiveUserMail($data['email']) ){ /** assume progress is logged */
                $customerId = Customer::where('email', $data['email'])->first()->id;
                $currentAttemptNumber = $this->findCurrentAttempt($customerId, $data['series']);
                if( $this->hasPaid($customerId, $currentAttemptNumber, $data['series']) )
                {
                    if( $this->hasFinished($customerId, $currentAttemptNumber, $data['series']) )
                    {
                        /** the attempt is complete -- start another */
                        DB::beginTransaction();
                        $createdCustomer = $customerId;
                        $progressPayload = [
                            'customer' => $createdCustomer,
                            'series' => $data['series'],
                            'attempt' => $currentAttemptNumber + 1,
                            'paid' => false,
                        ];
                        Progresitem::create($progressPayload);
                        /** payment for new users */
                        $paymentData = [
                            'email' => $data['email'],
                            'invoice_no' => (string)Str::uuid(),
                        ];
                        $apiReturnedPayload = $this->init_paygate($paymentData);
                        $paygateResponse = $apiReturnedPayload[0];
                        $initPayData = [
                            'customer' => $createdCustomer,
                            'amount' => Price::find(1)->normal,
                            'currency' => 'ZAR',
                            'ref' => $paymentData['invoice_no'],
                            'ext_ref' => $paygateResponse['uuid'],
                            'email' => $data['email'],
                            'req_payload' => json_encode($apiReturnedPayload[1]),
                            'init_payload' => json_encode($paygateResponse),
                        ];
                        Payment::create($initPayData);
                        DB::commit();
                        return redirect()->route('show_iframe', [
                            'pid' => $paygateResponse['uuid'],
                            'hash' => $paymentData['invoice_no'],
                        ]);
                        /** end of next attempt */
                    }
                    $data['attempt'] = $currentAttemptNumber;
                    $nextMeta = $this->findNextMeta($data);
                    return redirect()->route('show_section', [
                        'no' => $nextMeta[0],
                        'hash' => $nextMeta[1],
                        'attempt' => $nextMeta[2],
                    ]);
                }
                /** prepare payment */
                $paymentData = [
                    'email' => $data['email'],
                    'invoice_no' => (string)Str::uuid(),
                ];
                $apiReturnedPayload = $this->init_paygate($paymentData);
                $paygateResponse = $apiReturnedPayload[0];
                $initPayData = [
                    'customer' => Customer::where('email', $data['email'])->first()->id,
                    'amount' => Price::find(1)->normal,
                    'currency' => 'ZAR',
                    'ref' => $paymentData['invoice_no'],
                    'ext_ref' => $paygateResponse['uuid'],
                    'email' => $data['email'],
                    'req_payload' => json_encode($apiReturnedPayload[1]),
                    'init_payload' => json_encode($paygateResponse),
                ];
                Payment::create($initPayData);
                return redirect()->route('show_iframe', [
                    'pid' => $paygateResponse['uuid'],
                    'hash' => $paymentData['invoice_no'],
                ]);
            }
            DB::beginTransaction();
            $createdCustomer = Customer::create($data)->id;
            $progressPayload = [
                'customer' => $createdCustomer,
                'series' => $data['series'],
                'attempt' => 1,
                'paid' => false,
            ];
            Progresitem::create($progressPayload);
            /** payment for new users */
            $paymentData = [
                'email' => $data['email'],
                'invoice_no' => (string)Str::uuid(),
            ];
            $apiReturnedPayload = $this->init_paygate($paymentData);
            $paygateResponse = $apiReturnedPayload[0];
            $initPayData = [
                'customer' => $createdCustomer,
                'amount' => Price::find(1)->normal,
                'currency' => 'ZAR',
                'ref' => $paymentData['invoice_no'],
                'ext_ref' => $paygateResponse['uuid'],
                'email' => $data['email'],
                'req_payload' => json_encode($apiReturnedPayload[1]),
                'init_payload' => json_encode($paygateResponse),
            ];
            Payment::create($initPayData);
            DB::commit();
            return redirect()->route('show_iframe', [
                'pid' => $paygateResponse['uuid'],
                'hash' => $paymentData['invoice_no'],
            ]);
        } catch (\PDOException $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => 'Data integrity error. Get in touch for more info' ]);
        }
        catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    public function show_section($no, $hash, $attempt)
    {
        try {
            $sectionMeta = Section::find($no);
            $customerMeta = Customer::where('hash', $hash)->first();
            if(!is_object($customerMeta))
            {
                return response([
                    'error' => 'page you are looking for was not found.',
                ],404);
            }
            if( !$this->hasPaid($customerMeta->id, $attempt, $sectionMeta->series) )
            {
                return redirect()->route('welcome')->with([
                    'mess' => 'Please complete payment by providing the following information before you start answering your questionnaires.'
                ]);
            }
            if(!is_object($sectionMeta))
            {
                return response([
                    'error' => 'none of this even exists',
                ],404);
            }
            
            $progressSofar = $this->findProgressMeta($customerMeta->id, $attempt, $sectionMeta->series);
            if( strlen($progressSofar->prev_section) && $progressSofar->next_section != $no)
            {
                if(is_null($progressSofar->next_url))
                {
                    return redirect()->route('welcome')->with([
                    'mess' => 'No more sections left in the current attempt. If you want to attempt again, please fill in the information below and click next'
                    ]);
                }
                return redirect($progressSofar->next_url);
            }
            if( !strlen($progressSofar->prev_section) &&  $no != 1 )
            {
                $sectionMeta = Section::find(1);
                $next_url = route('show_section', [
                    'no' => 1,
                    'hash' => $hash,
                    'attempt' => $attempt,
                ]);
                return redirect($next_url);
            }
            $orgInvoice = $customerMeta->org_invoice;
            $orgMeta = Invoice::find($orgInvoice);
            $isClothingBank = 0;
            if( is_object($orgMeta) && in_array($orgMeta->org, [4,7,8,9,10]))
            {
                $isClothingBank = 1;
            }
            return view('show_section', [
                'sectionMeta' => $sectionMeta,
                'customerHash' => $hash,
                'attempt' => $attempt,
                'questions' => $this->findSectionQuestions($sectionMeta->id),
                'isClothingBank' => $isClothingBank,
            ]);
        } catch (\Throwable $th) {
            return response([
                'error' => 'System could not load your assessment. Try again later or contact ' . Config::get('mail.from.address') . ' for assistance ' . $th->getMessage(),
            ],404);
        }
    }
    public function save_section(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'section' => ['required', 'string'],
                'hash' => ['required', 'string'],
                'attempt' => ['required', 'string'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $data = $request->all();
            $attempt = $data['attempt'];
            $pool = $this->listSectionQuestions($data['section']);
            $customerMeta = Customer::where('hash', $data['hash'])->first();
            $sectionMeta = Section::find($data['section']);
            if(is_null($customerMeta))
            {
                throw new \Exception("No-user error. We have no user with this email key");
            }
            DB::beginTransaction();
            foreach ($pool as $_question) {
                $optId = $request->get('userOpt__' . $_question['id']);
                $optMeta = $this->getOptMeta($optId);
                if(!strlen($optId)) {
                    throw new \Exception("Missing Answers. Please make sure you answer all questions");
                }
                $running_sum = intval($this->findRunningSum($customerMeta->id, $data['section'], $attempt)) + intval($optMeta->value);
                $reponsePayload = [
                    'customer' => $customerMeta->id,
                    'section' => $data['section'],
                    'question' => $_question['id'],
                    'label' => $_question['name'],
                    'choice' => $optMeta->id,
                    'choice_text' => $optMeta->option,
                    'points' => $optMeta->value,
                    'running_sum' => $running_sum,
                    'attempt' => $attempt,
                ];
                /** save only if it doesnt exist custom->section->question->attempt */
                if( $this->responseEntryExists($reponsePayload) )
                {
                    $entryId = $this->getResponseEntryId($reponsePayload);
                    CustomerResponse::find($entryId)->update($reponsePayload);
                }else{
                    CustomerResponse::create($reponsePayload);
                }
            }
            $progressItem = Progresitem::where('customer', $customerMeta->id)
                ->where('series', $sectionMeta->series)
                ->where('attempt', $attempt)
                ->where('paid', true)
                ->first();
            if(is_null($progressItem)) {
                throw new \Exception("Error. Seems like you have not attempted older sections.");
            }
            $next_message = 'Thank you for completing this section. The next Questionnaire has been sent to your email.';
            $next_section = $data['section'] + 1;
            $next_url = route('show_section', [
                'no' => $next_section,
                'hash' => $customerMeta->hash,
                'attempt' => $attempt,
            ]);
            if( intval($data['section']) <= 16 )
            {
                $progressItem->prev_section = $data['section'];
                $progressItem->next_section = $next_section;
                $progressItem->next_url = $next_url;
                if( intval($data['section']) == 16 ) /** last one */
                {
                    $next_message = 'Thank you for completing Hardwires Assessments. Your Graph has been sent to your email.';
                    $progressItem->has_finished = 2;
                    $progressItem->next_section = null;
                    $progressItem->next_url = null;
                    /** mailings::: send graph */
                    $scoresMeta = $this->sum_user_scores($customerMeta->id, $attempt,  $sectionMeta->series);
                    $userRef = $this->getUserRef($customerMeta);
                    $graphData = [ $scoresMeta[0], $scoresMeta[1], $userRef ];
                    $graph = $this->generate_graph($graphData);
                    $finalDoc = $this->merge_pdf($graph);
                    // throw new \Exception(json_encode($graphData[0]));
                    $mailData = [
                        'sectionTitle' => 'Hardwires Assessment - Final Graph',
                        'userRef' => $userRef,
                        'userGraph' => $finalDoc,
                    ];
                    $this->update_user_chart([
                        'customer' => $customerMeta->id,
                        'graph' => $graph,
                        'full_doc' => $finalDoc,
                        'attempt' => $attempt,
                    ]);
                    Mail::to([$customerMeta->email, 'discover@hardwires.co.za'])->send(new Sectiongraph(json_decode(json_encode($mailData))));
                }else
                {
                    $nextSectionMeta = Section::find($next_section);
                    if(!is_object($nextSectionMeta))
                    {
                        throw new \Exception("No-section error. We have no section with this hash key");
                    }
                    /** mailings::: send next link */
                    $mailData = [
                        'sectionTitle' => 'Hardwires Assessment - ' . $nextSectionMeta->name,
                        'userRef' => $this->getUserRef($customerMeta),
                        'sectionLink' => $next_url,
                    ];
                    Mail::to($customerMeta->email)->send(new Nextsection(json_decode(json_encode($mailData))));
                }
                $progressItem->save();
            }
            DB::commit();
            return redirect()->route('tonext')->with([ 'suc' => $next_message ]);
        } catch (\PDOException $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => 'Data integrity error. Get in touch for more info ' . $e->getMessage()]);
        }
        catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    protected function responseEntryExists($entry)
    {
        return CustomerResponse::where('customer', $entry['customer'])
            ->where('section', $entry['section'])
            ->where('question', $entry['question'])
            ->where('attempt', $entry['attempt'])
            ->count() > 0;
    }
    protected function getResponseEntryId($entry)
    {
        $rtn = CustomerResponse::where('customer', $entry['customer'])
            ->where('section', $entry['section'])
            ->where('question', $entry['question'])
            ->where('attempt', $entry['attempt'])
            ->first();
        if( is_object($rtn) )
        {
            return $rtn->id;
        }
        throw new \Exception("Error Processing Request. Entry exists but could not be updated");
    }
    protected function getUserRef($customerMeta)
    {
        $nameArray = explode(' ', $customerMeta->name);
        $ref = substr($nameArray[0], 0, 2);
        if(isset($nameArray[1]))
        {
            $ref = $ref .  substr($nameArray[1], 0, 3);
        }
        return strtoupper($ref = $ref . '20210' . $customerMeta->id);
    }
    protected function findRunningSum($customer, $section, $attempt)
    {
        return CustomerResponse::where('customer', $customer)
            ->where('section', $section)
            ->where('attempt', $attempt)
            ->sum('points');
    }
    protected function getOptMeta($id)
    {
        $data = Option::find($id);
        if( is_null($data) ) 
        {
            throw new \Exception("Error occured. Question options not found");
        }
        return $data;
    }
    protected function findSectionQuestions($sectionId)
    {
        // $data = Question::where('section', $sectionId)->orderBy('number', 'asc')->get();
        $data = Question::selectRaw("id, name, description, section, number, is_check, is_active, created_at, updated_at, CAST(number as SIGNED) AS q_number")
        ->where('section', $sectionId)
        ->orderBy('q_number', 'asc')
        ->orderBy('number', 'asc')
        ->get();
        if( is_null($data) ) { return []; }
        return $this->formatQuestions($data->toArray());
    }
    protected function listSectionQuestions($sectionId)
    {
        // $data = Question::where('section', $sectionId)->orderBy('number', 'asc')->get();
        $data = Question::selectRaw("id, name, description, section, number, is_check, is_active, created_at, updated_at, CAST(number as SIGNED) AS q_number")
        ->where('section', $sectionId)
        ->orderBy('q_number', 'asc')
        ->orderBy('number', 'asc')
        ->get();
        if( is_null($data) ) { return []; }
        return $data->toArray();
    }
    protected function formatQuestions($data)
    {
        $rtn = [];
        foreach ($data as $_data) {
           $_data['options'] = $this->findQuestionOptions($_data['id']);
           array_push($rtn, $_data);
        }
        return $rtn;
    }
    protected function findQuestionOptions($id)
    {
        $data = Option::where('question', $id)->orderBy('id', 'asc')->get();
        if( is_null($data) ) { return []; }
        return $data->toArray();
    }
    protected function isActiveUserMail($email)
    {
        return Customer::where('email', $email)->count() > 0;
    }
    protected function hasPaid($customer, $attempt, $series)
    {
        $p = Progresitem::where('customer', $customer)
            ->where('attempt', $attempt)
            ->where('paid', true)
            ->where('series', $series)->count();
        return $p > 0;
    }
    protected function hasFinished($customer, $attempt, $series)
    {
        $nextMeta = Progresitem::where('customer', $customer)
            ->where('attempt', $attempt)
            ->where('paid', true)
            ->where('series', $series)->first();
        if(intval($nextMeta->has_finished) == 2)
        {
            return true;
        }
        return false;
    }
    protected function findNextMeta($data)
    {
        $meta = Customer::where('email', $data['email'])->first();
        if(is_null($meta))
        {
            throw new \Exception("User information error. User with that email not found");
        }
        $nextMeta = Progresitem::where('customer', $meta->id)
            ->where('attempt', $data['attempt'])
            ->where('paid', true)
            ->where('series', $data['series'])->first();
        if( is_null($nextMeta->prev_section) || !strlen($nextMeta->prev_section))
        {
            $no = $this->getSeriesFirstSection($data['series']);
            $hash = $data['hash'];
            return [$no, $hash, $data['attempt']];
        }
        return [$nextMeta->next_section, $data['hash'], $data['attempt']];
    }
    protected function isActiveUserPhone($phone)
    {
        return Customer::where('phone', $phone)->count() > 0;
    }
    protected function findProgressMeta($customer, $attempt, $series)
    {
        $meta = Progresitem::where('customer', $customer)
            ->where('attempt', $attempt)
            ->where('paid', true)
            ->where('series', $series)->first();
        if(is_null($meta))
        {
            throw new \Exception("Error occured.  No progress found for this user");
        }
        return $meta;
    }
    protected function getSeriesFirstSection($series)
    {
        $d = Section::where('series', $series)->orderBy('id', 'asc')->first();
        if(is_null($d))
        {
            throw new \Exception("Error occured.  No sections found in the questionnaire");
        }
        return $d->id;
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
            'amount' => Price::find(1)->normal,
            'item_name' => 'HardWires Assessment Package Purchase',
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
    protected function generate_graph($data)
    {
        $pdf_data = [
            'scores' => $data[0],
            'heights' => $data[1],
            'ref' => $data[2],
        ];
        $uuid_string = (string)Str::uuid() . '.pdf';
        $filename = ('app/graphs/' . $uuid_string);
        PDF::loadView('mails.graph', $pdf_data)->save(storage_path($filename));
        return $filename;
    }
    protected function random_vals($count = 15)
    {
        $scores = $heights = [];
        $loop = 0;
        while( $loop < $count)
        {
            $r = round(mt_rand(1, 95));
            array_push($scores, $r);
            array_push($heights, round($r * 3.73));
            $loop++;
        }
        return [$scores, $heights];
    }
    protected function merge_pdf($first)
    {
        $finalFile = (string)Str::uuid() . '.pdf';
        $mergeWith = 'app/graphs/HardWires.pdf';
        $merger = new Merger();
        $merger->addIterator([ storage_path($first), storage_path($mergeWith)]);
        $pdf = $merger->merge();
        $final_path = 'app/graphs/' . $finalFile;
        file_put_contents(storage_path($final_path), $pdf);
        return $final_path;
    }
    protected function update_user_chart($data)
    {
        if( Chart::where('customer', $data['customer'])->where('attempt', $data['attempt'])->count() )
        {
            $ch = Chart::where('customer', $data['customer'])->first();
            $ch->graph = $data['graph'];
            $ch->full_doc = $data['full_doc'];
            $ch->attempt = $data['attempt'];
            $ch->save();
            return;
        }
        Chart::create($data);
        return;
    }
    protected function sum_user_scores($customer, $attempt, $series)
    {
        $data = Section::select('id')->where('series', $series)->orderBy('id', 'asc')->get();
        if(is_object($data))
        {
            $sections = $data->toArray();
            $scores = $heights = [];
            foreach ($sections as $_section) {
                if( intval($_section['id']) != 4) /** exempt ZIZO */
                {
                    $questionsInSection = Question::where('section', $_section['id'])->count();
                    $r = $this->sum_section_score($customer, $_section['id'], $attempt);
                    $scorePercent = round((($r * 100)/$questionsInSection));
                    /** set max to 98 */
                    $scorePercent = $scorePercent < 100 ? $scorePercent : 98;
                    array_push($scores, $scorePercent);
                    array_push($heights, round($scorePercent * 3.73));
                }
            }
            return [ $scores, $heights ];
        }
        throw new \Exception("Error Processing Request. No sections found for passed series.");
    }
    protected function sum_section_score($customer, $section, $attempt)
    {
        return CustomerResponse::where('customer', $customer)
            ->where('attempt', $attempt)
            ->where('section', $section)->sum('points');
    }


    public function test_graph()
    {
        $s = "PAYGATE_ID=1042890100014&PAY_REQUEST_ID=A39B064E-1B17-94A1-9976-415719B39696&REFERENCE=c33a697c-f617-4e59-9e3c-32b928c4ebf4&TRANSACTION_STATUS=1&RESULT_CODE=990017&AUTH_CODE=910907&CURRENCY=ZAR&AMOUNT=135&RESULT_DESC=Auth Done&TRANSACTION_ID=285609875&RISK_INDICATOR=AP&PAY_METHOD=CC&PAY_METHOD_DETAIL=Visa&CHECKSUM=f235cca75e1c3c04453e5bb657f9c531&";
        // return $this->urltoarr($s);

        $gdata = $this->random_vals();
        $gdata[2] = 'MKUNGS20210034';
        $graph = $this->generate_graph($gdata);
        $finalDoc = $this->merge_pdf($graph);
        $mailData = [
            'sectionTitle' => 'Hardwires Assessment - Final Graph',
            'userRef' => 'MKUNGS20210034',
            'userGraph' => $finalDoc,
        ];
        $this->update_user_chart([
            'customer' => 234,
            'graph' => $graph,
            'full_doc' => $finalDoc,
        ]);
        Mail::to('iotuya05@gmail.com')->send(new Sectiongraph(json_decode(json_encode($mailData))));
    }
    protected function urltoarr($str)
    {
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
}
