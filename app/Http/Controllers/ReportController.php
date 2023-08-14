<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transection;

class ReportController extends Controller
{
    //

    public function __construct(){
        $this->middleware("auth:api");
    }

    public function created_report(Request $request){

        $month_type = $request->month_type;
        $dmy = $request->dmy;

        $m = explode("-",$dmy)[1];
        $y = explode("-",$dmy)[0];

        if($month_type == "m"){

            $income = Transection::where("tran_type","income")
            ->whereYear("created_at","=",$y)
            ->whereMonth("created_at","=",$m)
            ->get();

            $expense = Transection::where("tran_type","expense")
            ->whereYear("created_at","=",$y)
            ->whereMonth("created_at","=",$m)
            ->get();

        } else if($month_type == "y"){

            $income = Transection::where("tran_type","income")
            ->whereYear("created_at","=",$y)
            ->get();

            $expense = Transection::where("tran_type","expense")
            ->whereYear("created_at","=",$y)
            ->get();

        }

        $response = [
            "income"=> $income,
            "expense"=> $expense
       ];

       return response()->json($response);
    }
}
