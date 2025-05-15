<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Manila');

class CommonTermsController extends Controller
{
    public $ip_add;

    public function __construct(){
        // $this->ip_add = \Request::ip();
        $this->ip_add = request()->ip();

    }

    public function save_common_term(Request $req){
        // return $req->english_term;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('common_terms')
        ->insert([
            'search_term' => $req->search_term,
            'english_term' => $req->english_term,
            'japanese_term' => $req->japanese_term,
            'created_by' => $this->ip_add,
            'created_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_common_term(Request $req){
        // return $req;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('common_terms')
        ->where('id',$req->id)
        ->update([
            'search_term' => $req->search_term,
            'english_term' => $req->english_term,
            'japanese_term' => $req->japanese_term,
            'updated_by' => $this->ip_add,
            'updated_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function get_common_terms(){
        $query = DB::connection('6060')
        ->table('common_terms')
        ->whereNull('deleted_by')
        ->get();

        return $query;
    }

    public function delete_common_term(Request $req){
        // return $req;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('common_terms')
        ->where('id',$req->id)
        ->update([
            'deleted_by' => $this->ip_add,
            'deleted_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);

    }
}
