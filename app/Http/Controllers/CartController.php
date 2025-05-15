<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function update_cart_judgement(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('print_pcg_copy')
        ->where('id',$req->id)
        ->update([
            'judgement' => $req->judgement,
        ]);

        return response()->json([
            'message' => 'judgement updated successfully',
        ]);
    }
    
    public function update_cart_pages(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('print_pcg_copy')
        ->where('id',$req->id)
        ->update([
            $req->column_name => $req->pages
        ]);

        return response()->json([
            'message' => 'page updated successfully',
        ]);
    }

    public function update_cart_action(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('print_pcg_copy')
        ->where('id',$req->id)
        ->update([
            'action_id' => $req->action_id,
        ]);

        return response()->json([
            'message' => 'action updated successfully',
        ]);
    }

    public function get_print(Request $req){
        $query = DB::connection('6060')
        ->table('print_pcg_copy')
        ->select(
            'print_pcg_copy.id as cart_id',
            'print_pcg_copy.pcg_id',
            'print_pcg_copy.action_id',
            'print_pcg_copy.print_by',
            'print_pcg_copy.print_val',
            'print_pcg_copy.judgement',
            'print_pcg_copy.pcg_reference_page_cart',
            'print_pcg_copy.pcg_reference_2_page_cart',
            'print_pcg_copy.pcg_reference_3_page_cart',
            'print_pcg_copy.pcg_reference_4_page_cart',
            'print_pcg_copy.pcg_shio_page_cart',
            'print_pcg_copy.pcg_hrd_memo_page_cart',
            'print_pcg_copy.pcg_hrd_memo_2_page_cart',
            'print_pcg_copy.pcg_hrd_memo_3_page_cart',
            'print_pcg_copy.pcg_hrd_memo_4_page_cart',
            'carte_pcg_copy.*',
            'actions.english as action_english',
            'actions.japanese as action_japanese',
        )
        ->leftJoin('carte_pcg_copy','print_pcg_copy.pcg_id' ,'=','carte_pcg_copy.id')
        ->leftJoin('actions','print_pcg_copy.action_id','=','actions.action_id')
        ->whereNotNull('carte_pcg_copy.pcg_code')
        ->where('print_pcg_copy.print_by',$req->EmployeeCode)
        ->where(function ($query) use($req) {
            $query->where('carte_pcg_copy.pcg_english','LIKE',"%$req->cart_search%")
                ->orWhere('carte_pcg_copy.pcg_japanese','LIKE',"%$req->cart_search%");
        })
        ->where('carte_pcg_copy.approve_status', '<>', '3')
        ->whereNull('carte_pcg_copy.delete_date')
        ->whereNotNull('carte_pcg_copy.checked_by')
        ->orderBy('print_pcg_copy.created_at','desc')
        ->get();

        return $query;
    }

    public function delete_print(Request $req){
        DB::connection('6060')
        ->table('print_pcg_copy')
        ->where('id',$req->cart_id)
        ->delete();
        
        return [
            'message' => 'cart item deleted'
        ];
       
    }

    public function delete_multiple_print(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('print_pcg_copy')
        ->whereIn('pcg_id', $req->pcg_ids)
        ->where('print_by',$req->print_by)
        ->delete();
        // ->delete();
        return [
            'message' => 'multiple cart item delete!'
        ];
    }

    public function insert_print(Request $req){
        // return $req;
        $arr1 = DB::connection('6060')
        ->table('print_pcg_copy')
        ->where('print_by','=',$req->user)
        ->where('pcg_id','=',$req->id)
        ->get();

        if(!count($arr1)){ //if walang laman ang arr1 insert
            DB::connection('6060')
            ->table('print_pcg_copy')
            ->insert([
                'pcg_id' => $req->id,
                'action_id' => 1,
                // 'print' => 0,
                'print_by' => $req->user,
                // 'judgement' => 'NG',
            ]);
        }
        return $arr1;
        
    }

    public function update_print(Request $req){
        // return $req;
        $query = DB::connection('6060')
        ->table('print_pcg_copy')
        // ->where('id',$req->id)
        ->whereIn('id',$req->ids);

        if($req->reset_val){
            $query->update([
                'print_val' => $req->print_val,
                'action_id' => $req->reset_val,
            ]);
        }else{
            $query->update([
                'print_val' => $req->print_val,
            ]);
        }

        return [
            'message' => 'item updated succesfully'
        ];
    }
}
