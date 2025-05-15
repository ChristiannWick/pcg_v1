<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckedController extends Controller
{
    public function getCheckedTemplates(){ //for approval
        $query = DB::connection('6060')
        ->table('carte_pcg_copy as t1')
        ->select(
            't1.*',
            't2.name as team_name'
        )
        ->Join('teams_copy as t2', 't1.pcg_team_code', '=', 't2.pcg_team_code')
        ->where('t1.approve_status',"1")
        ->whereNotNull('pcg_code')
        ->whereNull('t1.delete_date')
        ->whereNotNull('t1.checked_by')
        ->orderBy('id','desc')
        ->get();

        return $query;
    }

    public function getForApprovalTemplates(){  //for checking
        $query = DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('approve_status',"1")
        ->whereNotNull('pcg_code')
        ->whereNull('delete_date')
        ->whereNull('checked_by')
        ->get();

        return $query;
    }

    public function getApprovedTemplates(){ //approved pcg
        $query = DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('approve_status',"2")
        ->whereNotNull('pcg_code')
        ->whereNull('delete_date')
        ->get();

        return $query;
    }

    public function approveTemplates(Request $req){
        // return $req;
        $jsonData = $req->checked_templates;
        // return gettype($jsonData);
        if(is_array($jsonData) && count($jsonData) > 0){
            // return $jsonData;
            foreach ($jsonData as $template) {
                DB::connection('6060')
                ->table('carte_pcg_copy')
                ->where('id', $template['id'])
                ->update([
                    'approved_by' => $req->approved_by,
                    'approved_at' => date("Y-m-d H:i:s"),
                    'disapproved_by' => null,
                    'disapproved_at' => null,
                    'approve_status' => 2
                ]);
            }
            return response()->json(['message' => 'Templates approved successfully']);

        }else{
            return response()->json(['message' => 'withot approved templates']);
        }
        

    }   

    public function countDuplicate(){
        $sql = "
        SELECT
        pcg_english,
        COUNT(pcg_english)
        FROM 
        carte_pcg_copy
        GROUP BY pcg_english
        HAVING COUNT(pcg_english) > 1;
        ";
    }
    
}
