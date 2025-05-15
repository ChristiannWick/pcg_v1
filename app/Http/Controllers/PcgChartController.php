<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Manila');


class PcgChartController extends Controller
{
    public function getChart(Request $req)
    {
        return DB::connection('6060')
        ->select(DB::raw("

            SELECT
            pcg_type,
            planner_roma,
            salesman_roma, 
            code
            FROM use_carte_pcg 
            WHERE date BETWEEN '".$req->dateFrom."' AND '".$req->dateTo."'
            
        "));
    }

    
}
