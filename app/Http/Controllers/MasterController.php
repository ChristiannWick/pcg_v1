<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Manila');

class MasterController extends Controller
{
    public $ip_add;

    public function __construct(){
        // $this->ip_add = \Request::ip();
        $this->ip_add = request()->ip();

    }

    public function checkAdress($ip){
        if($this->ip_add != $ip){
            return false; //0
        }else{
            return true; //1
        }
    }

    public function get_versions(Request $request){
        // return $request;
        $query1 = DB::connection('6060')
        ->table('versions')
        ->whereNull('deleted_at')
        ->orderBy('id','desc')
        ->first();
        // ->get();
        // return $query1;

        $query2 = DB::connection('6060')
        ->table('users_copy')
        ->whereNull('deleted_date')
        ->where('id',$request->id)
        ->first();
        // ->get();
        $update_val = '';
        if($query1->id != $query2->version_id){
            $update_val = DB::connection('6060')
            ->table('users_copy')
            ->where('id',$request->id)
            ->update([
                'version_id' => $query1->id,
            ]);
            
        }

        return response()->json([
            'query1' => $query1,
            'update_val' => $update_val,
        ]);

        // return $query1;

    }

    public function get_actions(){
        $query = DB::connection('6060')
        ->table('actions')
        ->select(
            'action_id',
            'english',
            'japanese',
            DB::raw("CONCAT('ACT',action_id,' ',english) AS new_english")
        )
        ->whereNull('deleted_at')
        ->get();

        return $query;
    }

    public function save_action(Request $req){
        // return $req;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('actions')
        ->insert([
            'english' => $req->english,
            'japanese' => $req->japanese,
            'created_by' => $this->ip_add,
            'created_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_action(Request $req){
        // return $req;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('actions')
        ->where('action_id',$req->id)
        ->update([
            'english' => $req->english,
            'japanese' => $req->japanese,
            'updated_by' => $this->ip_add,
            'updated_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function delete_action(Request $req){
        // return $req;
        $date_today = date('Y-m-d');
        DB::connection('6060')
        ->table('actions')
        ->where('action_id',$req->id)
        ->update([
            'deleted_by' => $this->ip_add,
            'deleted_at' => $date_today,
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);

    }

    public function get_methods(){
        $query = DB::connection('6060')
        ->table('methods_old')
        ->select(
            'id',
            'pcg_method'
        )
        ->whereNull('deleted_by')
        ->get();

        return $query;
    }

    public function save_method(Request $req){
        // return $req;

        DB::connection('6060')
        ->table('methods_old')
        ->insert([
            'pcg_method' => str_replace(['"',"'"," "], '', ucfirst(strtolower($req->pcg_method))),
            'date_created' => date("Y-m-d h:i:s"),
            'created_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_method(Request $req){
        // return $req;

        DB::connection('6060')
        ->table('methods_old')
        ->where('id',$req->id)
        ->update([
            'pcg_method' => str_replace(['"',"'"," "], '', ucfirst(strtolower($req->pcg_method))),
            'date_updated' => date("Y-m-d h:i:s"),
            'updated_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function delete_method(Request $req){
        // return $req->id;
      
        $test = DB::connection('6060')
        ->table('methods_old')
        ->where('id',$req->id)
        ->update([
            'date_deleted' => date("Y-m-d h:i:s"),
            'deleted_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);
    }

    public function get_pcgtypes(){
        $query = DB::connection('6060')
        ->table('pcg_types')
        ->select(
            'id',
            'pcg_type',
            'pcg_code',
        )
        ->whereNull('deleted_by')
        ->get();

        return $query;
    }

    public function save_pcgtype(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('pcg_types')
        ->insert([
            'pcg_type' => str_replace(['"',"'"], '', $req->pcg_type),
            'pcg_code' => str_replace(['"',"'"," "], '', strtoupper($req->pcg_code)),
            'date_created' => date("Y-m-d h:i:s"),
            'created_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_pcgtype(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('pcg_types')
        ->where('id',$req->id)
        ->update([
            'pcg_type' => str_replace(['"',"'"], '', $req->pcg_type),
            'pcg_code' => str_replace(['"',"'"," "], '', strtoupper($req->pcg_code)),
            'date_updated' => date("Y-m-d h:i:s"),
            'updated_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function delete_pcgtype(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('pcg_types')
        ->where('id',$req->id)
        ->update([
            'date_deleted' => date("Y-m-d h:i:s"),
            'deleted_by' => $this->ip_add
        ]);
        
        return response()->json([
            'message' => 'item deleted!'
        ]);
    }

    public function get_categories(){
        $query = DB::connection('6060')
        ->table('carte_pcg_category')
        ->select(
            'id',
            'category',
        )
        ->where('date_deleted',null)
        ->get();
        return $query;
    }

    public function save_category(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('carte_pcg_category')
        ->insert([
            // 'code' => str_replace(['"',"'"], '', strtoupper($req->code)),
            'category' => str_replace(['"',"'"], '', strtoupper($req->category)),
            'date_created' => date("Y-m-d h:i:s"),
            'created_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_category(Request $req){
        // return $req->id;
        DB::connection('6060')
        ->table('carte_pcg_category')
        ->where('id',$req->id)
        ->update([
            // 'code' => str_replace(['"',"'"], '', strtoupper($req->code)),
            'category' => str_replace(['"',"'"], '', strtoupper($req->category)),
            'date_updated' => date("Y-m-d h:i:s"),
            'updated_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function delete_category(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('carte_pcg_category')
        ->where('id',$req->id)
        ->update([
            'date_deleted' => date("Y-m-d h:i:s"),
            'deleted_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);
    }

    public function get_teams(){
        $query = DB::connection('6060')
        ->table('teams_copy')
        ->select(
            // 'id',
            'pcg_team_code',
            'name',
        )
        ->where('date_deleted',null)
        ->get();

        return $query;
    }

    public function save_team(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('teams_copy')
        ->insert([
            // 'pcg_team_code' => $req->pcg_team_code,
            'name' => str_replace(['"',"'"], '', $req->name),
            'date_created' => date("Y-m-d h:i:s"),
            'created_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function update_team(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('teams_copy')
        ->where('pcg_team_code',$req->pcg_team_code)
        ->update([
            // 'pcg_team_code' =>  $req->pcg_team_code,
            'name' => str_replace(['"',"'"], '', $req->name),
            'date_updated' => date("Y-m-d h:i:s"),
            'updated_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function delete_team(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('teams_copy')
        ->where('pcg_team_code',$req->pcg_team_code)
        ->update([
            'date_deleted' => date("Y-m-d h:i:s"),
            'deleted_by' => $this->ip_add
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);
    }

    public function getUsers(Request $req){
        // return $req;
        $testQuery = DB::connection('6060')
        ->table('users_copy as t1')
        ->select(
            't1.id',
            't1.EmployeeCode', 
            't1.EmployeeName',
            't1.NickName', 
            't1.TeamCode as pcg_team_code', 
            't1.password', 
            't1.admin', 
            't1.ipaddress',
            't1.version_id',
            't2.name as TeamName',
            DB::raw("CASE 
                WHEN t1.admin = 0 THEN 'Member' 
                WHEN t1.admin = 2 THEN 'Support' 
                ELSE 'Admin' 
                END as role"),
            // 't3.name as version_name'
        )
        ->leftJoin('teams_copy as t2','t1.TeamCode','=','t2.pcg_team_code')
        ->leftJoin('versions as t3','t1.version_id','=','t3.id')
        // ->whereNotIn('t1.EmployeeCode',['36396','20042','06995'])
        ->where('t1.deleted_date',null)
        ->where('t1.EmployeeCode','LIKE',$req->user_id."%")
        ->whereNull('t1.deleted_by');
        // ->where('EmployeeCode',$req->EmployeeCode)
        // ->orderBy('pcg_team_code','asc')
        // ->orderBy('EmployeeCode','asc')
        // ->get();

        // if($req->admin == 0){
        //     $testQuery->where('EmployeeCode', $req->EmployeeCode);
        // }

        $testResult = $testQuery
        ->orderBy('t1.EmployeeCode', 'asc')
        ->get();


        return $testResult;


    }

    
    public function addUser(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('users_copy')
        ->insert([
            'EmployeeCode' => $req->EmployeeCode,
            'EmployeeName' => $req->EmployeeName,
            'NickName' => $req->NickName,
            'TeamCode' => $req->pcg_team_code,
            'password' => $req->password,
            'admin' => $req->admin,
            // 'ipaddress' => $req->ipaddress,
            'created_date' =>  date("Y-m-d h:i:s"),
            'created_by' =>  $this->ip_add,
        ]);

        return response()->json([
            'message' => 'item inserted!'
        ]);
    }

    public function updateUsers(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('users_copy')
        ->where('id',$req->id)
        ->update([
            'EmployeeCode' => $req->EmployeeCode,
            'EmployeeName' => $req->EmployeeName,
            'NickName' => $req->NickName,
            'TeamCode' => $req->pcg_team_code,
            'password' => $req->password,
            'admin' => $req->admin,
            // 'ipaddress' => $req->ipaddress,
            'updated_date' => date("Y-m-d h:i:s"),
            'updated_by' =>  $this->ip_add,
        ]);

        return response()->json([
            'message' => 'item updated!'
        ]);
    }

    public function deleteUser(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('users_copy')
        ->where('id',$req->id)
        ->update([
            'deleted_date' => date("Y-m-d h:i:s"),
            'deleted_by' =>  $this->ip_add,
        ]);

        return response()->json([
            'message' => 'item deleted!'
        ]);
        
    }
    
    public function insert_test_carte(Request $req){
        // return $req;
        // return $req->datas;
        if($req->with_data){
            // return $req->with_data['CustomerCode'];
            $bulkInsertData = [
                'management_code' => $req->with_data['CustomerCode'],
                'revision_number' => $req->with_data['PlanNumber'],
                'plan_code' => $req->with_data['plan_code'],
                'app_type' => $req->with_data['app_type'],
                'plan_type' => $req->with_data['HouseName'],
                'floor' => $req->with_data['AllFloor'],
                'building_x_direction' => $req->with_data['WidthXDirection'],
                'building_y_direction' => $req->with_data['DepthYDirection'],
                'construction_area_square_merter' => $req->with_data['SekouMenseki_meter'],
                'construction_area_tubo' => $req->with_data['SekouMenseki'],
                'entrance_east' => $req->with_data['EntranceLocation_east'],
                'entrance_west' => $req->with_data['EntranceLocation_west'],
                'entrance_south' => $req->with_data['EntranceLocation_south'],
                'entrance_north' => $req->with_data['EntranceLocation_north'],
                'rooms_num_floor1' => $req->with_data['Rooms1F'],
                'rooms_num_floor2' => $req->with_data['Rooms2F'],
                'stairs_living' => $req->with_data['StairLiving'],
                'stairs_hall' => $req->with_data['StairHall'],
                'stairs_open' => $req->with_data['StairsOpen'],
                'roof_garden' => $req->with_data['RoofGarden'],
                'den' => $req->with_data['StudyRoom'],
                'two_family_house' => $req->with_data['Family_2'],
                'bath_25' => $req->with_data['UnitBathSize'],
                'wic_floor1' => $req->with_data['WalkInCloset'],
                'wic_floor2' => $req->with_data['WalkInCloset2'],
            ];
            DB::connection('6060')
            ->table('drio')
            ->insert($bulkInsertData);
        }else{
            $bulkInsertData = [
                'management_code' => $req->without_data['CustomerCode'],
                'revision_number' => $req->without_data['PlanNumber'],
                'plan_code' => $req->without_data['plan_code'],
                'app_type' => $req->without_data['app_type'],
            ];
            
            DB::connection('6060')
            ->table('no_drio')
            ->insert($bulkInsertData);
        }

        return response()->json([
            'messsage' => 'success insert',
            // 'data' => $req->with_data
        ]);

        // foreach ($req->with_data as $key => $value) {
        //     // return $value->BillingMonth;
        //     $bulkInsertData = [
        //         'management_code' => $value->CustomerCode,
        //         'revision_number' => $value->PlanNumber,
        //         'plan_code' => $value->plan_code,
        //         'app_type' => $value->app_type,
        //         'plan_type' => $value->HouseName,
        //         'floor' => $value->AllFloor,
        //         'building_x_direction' => $value->WidthXDirection,
        //         'building_y_direction' => $value->DepthYDirection,
        //         'construction_area_square_merter' => $value->SekouMenseki_meter,
        //         'construction_area_tubo' => $value->SekouMenseki,
        //         'entrance_east' => $value->EntranceLocation_east,
        //         'entrance_west' => $value->EntranceLocation_west,
        //         'entrance_south' => $value->EntranceLocation_south,
        //         'entrance_north' => $value->EntranceLocation_north,
        //         'rooms_num_floor1' => $value->Rooms1F,
        //         'rooms_num_floor2' => $value->Rooms2F,
        //         'stairs_living' => $value->StairLiving,
        //         'stairs_hall' => $value->StairHall,
        //         'stairs_open' => $value->StairsOpen,
        //         'roof_garden' => $value->RoofGarden,
        //         'den' => $value->StudyRoom,
        //         'two_family_house' => $value->Family_2,
        //         'bath_25' => $value->UnitBathSize,
        //         'wic_floor1' => $value->WalkInCloset,
        //         'wic_floor2' => $value->WalkInCloset2,
        //     ];
        //     DB::connection('6060')
        //     ->table('drio')
        //     ->insert($bulkInsertData);
        // }

        // foreach ($req->without_data as $key => $value) {
        //     // return $value->BillingMonth;
        //     $bulkInsertData = [
        //         'management_code' => $value->CustomerCode,
        //         'revision_number' => $value->PlanNumber,
        //         'plan_code' => $value->plan_code,
        //         'app_type' => $value->app_type,
        //     ];
        //     DB::connection('6060')
        //     ->table('no_drio')
        //     ->insert($bulkInsertData);
        // }

        


        $new_arr = [];
        foreach ($req->datas as $key => $value) {
            // # code...
            array_push($new_arr,$value['construction_code']);
        }
        // return $new_arr;

        return $array3 = DB::connection('HRDSQL')
        ->table('Constructions')
        ->select('ConstructionCode','ConstructionName')
        ->whereIn('ConstructionCode', $new_arr)
        ->get();

        return response()->json([
            'data1' => $req->datas,
            'data2' => $array3,
        ]);

        // foreach ($req->datas as $key => $value) { // insert data to table w009
        //     // return $value['approve_staus'];
        //     foreach ($value as $field => $data) {
        //         $value[$field] = str_replace(['"', "'"], '', $data);
        //     }
        //     $cleanedPcgEnglish = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $value['pcg_english']);
        //     $cleanedPcgJapanese = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $value['pcg_japanese']);
        //     DB::connection('6060')
        //     ->table('test_carte_pcg_copy')
        //     ->insert([
        //         'pcg_type' => $value['pcg_type'],
        //         'pcg_code' => $value['pcg_code'],
        //         'pcg_english' =>  $cleanedPcgEnglish,
        //         'pcg_japanese' => $cleanedPcgJapanese,
        //         'pcg_reference_2' => $value['pcg_reference_2'] == ""?NULL:$value['pcg_reference_2'],
        //         'pcg_method' => $value['pcg_method'],
        //         'pcg_incharge' => $value['pcg_incharge'],
        //         'pcg_translated_by' => $value['pcg_translated_by'],
        //         'stop_checkpoints' => $value['stop_checkpoints'],
        //         'approve_status' => "1" ,
        //         'remarks_status' => $value['remarks_status'],
        //         'print' => 0,
        //         'remarks' => $value['remarks']
            
        //     ]);
        // }

        // return 'success';

    }
}
