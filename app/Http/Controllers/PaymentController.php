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
use App\Models\Corporate;
use App\Models\Invoice;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Initsection;
use App\Mail\Sectiongraph;
use App\Mail\PaymentReceived;
use Config;
use Storage;

class PaymentController extends Controller
{

    public function show_iframe($pid, $hash)
    {
        return view('show_iframe', [
            'PAY_REQUEST_ID' => $pid,
            'CHECKSUM' => $hash,
        ]);
    }
    public function thank_you()
    {
        return view('thank_you');
    }
    public function c_thank_you()
    {
        return view('c_thank_you');
    }
    public function p_cancel()
    {
        return view('p_cancel');
    }
    public function tonext()
    {
        return view('tonext');
    }
    protected function IpnToString($data)
    {
        return http_build_query($data);
    }
    protected function compareSignature($ipnString, $signature)
    {
        $ipnString = $ipnString . '&passphrase=' . Config::get('app.pg_phrase');
        $new_signature = md5($ipnString);
        return $signature === $new_signature;
    }
    public function callback()
    {
        header( 'HTTP/1.0 200 OK' );
        flush();
        // $data = file_get_contents('php://input');
        $data = $_POST;
        try {
            if(!$data) {
                throw new \Exception("Payment error. EMPTY DATA"); 
            }
            if(!is_array($data)) { 
                throw new \Exception("Payment error. EMPTY DATA"); 
            } 
            Storage::disk('local')->append('paygate.txt', json_encode($data));
            $responseArray = $data;
            unset($data['signature']);/** remove sign */
            $signature = $responseArray['signature'];
            $ipn_string = $this->IpnToString($data);
            if( !$this->compareSignature($ipn_string, $signature) )
            {
                throw new \Exception("Payment info error. Invalid signature");
            }
            if( $responseArray['payment_status'] != 'COMPLETE' ) /** transaction failed */
            {
                return;
            }
            $paymentMeta = Payment::where('ref', $responseArray['m_payment_id'])->first();
            if(is_null($paymentMeta))
            {
                throw new \Exception("Payment info error. Payment with that REF not found");
            }
            /** for corporate payment */
            if( strlen($responseArray['m_payment_id']) < 20 )
            {
                /** save payment option */
                $invoiceNumber = $responseArray['m_payment_id'];
                $paymentMeta->paid_amount = $responseArray['amount_gross'];
                $paymentMeta->payload = json_encode($responseArray);
                $paymentMeta->is_paid = true;
                $paymentMeta->save();
                $invoiceMeta = Invoice::find($invoiceNumber);
                $corporateMeta = Corporate::find($invoiceMeta->org);
                $invoiceMeta->is_paid = 1;
                $invoiceMeta->paid_sum = $responseArray['amount_gross'];
                $invoiceMeta->save();
                $payData = [
                    'title' => 'Payment Acknowledgement',
                    'name' => $corporateMeta->name,
                    'ref' => $responseArray['pf_payment_id'],
                    'amount' => 'R ' . number_format(($responseArray['amount_gross']), 2),
                    'zar' => 'ZAR',
                    'method' => 'NA',
                ];
                Mail::to($corporateMeta->email)->send(new PaymentReceived(json_decode(json_encode($payData))));
                return;
            }
            $customerMeta = Customer::find($paymentMeta->customer);
            $customerMeta->has_paid = true;
            $customerMeta->save();
            /** progress being paid for */
            $progressMeta = Progresitem::where('customer', $customerMeta->id)
                ->where('paid', false)
                ->orderby('id', 'desc')->first();
            if( !is_object($progressMeta) )
            {
                throw new \Exception("Error Processing Request. Payment made but progress item not found");
            }
            $progressMeta->paid = true;
            $progressMeta->save();
            $nextMeta = $this->findNextMeta($customerMeta->id, $progressMeta->attempt, $progressMeta->series, $customerMeta->hash);
            /** save payment option */
            $paymentMeta->paid_amount = $responseArray['amount_gross'];
            $paymentMeta->payload = json_encode($responseArray);
            $paymentMeta->is_paid = true;
            $paymentMeta->save();
            /** mail this user */
            $sectionMeta = Section::find($nextMeta[0]);
            $mailData = [
                'sectionTitle' => 'Hardwires Assessment - ' . $sectionMeta->name,
                'userRef' => $this->getUserRef($customerMeta),
                'sectionLink' => route('show_section', [ 'no' => $nextMeta[0], 'hash' => $nextMeta[1], 'attempt' => $nextMeta[2]]),
            ];
            $payData = [
                'title' => 'Payment Acknowledgement',
                'name' => $customerMeta->name,
                'ref' => $responseArray['pf_payment_id'],
                'amount' => 'R ' . number_format(($responseArray['amount_gross']), 2),
                'zar' => 'ZAR',
                'method' => 'NA',
            ];
            Mail::to($customerMeta->email)->send(new PaymentReceived(json_decode(json_encode($payData))));
            Mail::to($customerMeta->email)->send(new Initsection(json_decode(json_encode($mailData))));
            return;
        } catch (\Throwable $th) {
            Storage::disk('local')->append('paygate.log', $th->getMessage());
            return;
        }
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
    protected function findNextMeta($customer, $attempt, $series, $hash)
    {

        $nextMeta = Progresitem::where('customer', $customer)
            ->where('attempt', $attempt)
            ->where('paid', true)
            ->where('series', $series)->first();
        if(intval($nextMeta->has_finished) == 2)
        {
            return [0,0,0];
        }
        if( is_null($nextMeta->prev_section) )
        {
            $no = $this->getSeriesFirstSection($series);
            return [$no, $hash, $attempt];
        }
        return [$nextMeta->next_section, $hash, $attempt];
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
    protected function urlEncodedToArray($str)
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
}
