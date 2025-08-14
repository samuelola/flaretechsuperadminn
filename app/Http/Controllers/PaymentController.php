<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function Payments(Request $request)
    {
         $payments = Payment::get();
         return view('dashboard.pages.payments.payment',compact('payments'));
    }

    public function Earnings(Request $request)
    {
       return view('dashboard.pages.payments.earnings');
    }

    public function splitSheet(Request $reques)
    {
        return view('dashboard.pages.payments.split_sheet');
    }
}
