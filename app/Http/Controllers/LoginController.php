<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Manila');


class LoginController extends Controller
{
    public function getUserData(Request $data){
        return DB::connection('HRDSQL')
        ->select(DB::raw("
            SELECT 
            E.EmployeeCode, 
            E.NickName, 
            E.EmployeeName, 
            E.DepartmentCode, 
            E.SectionCode, 
            dpm.DepartmentName, 
            s.SectionName, 
            E.RetiredDate,
            E.DesignationCode
            FROM HRDSQL.CompanyInformation.dbo.Employees E
            left JOIN HRDSQL.CompanyInformation.dbo.Departments dpm
            ON E.DepartmentCode = dpm.DepartmentCode
            left JOIN HRDSQL.CompanyInformation.dbo.Sections s
            ON E.SectionCode = s.SectionCode
            WHERE E.EmployeeCode = '$data->employee_id'

        "));
    }

    public function insertUserData(Request $req){
        // return $req;
        $clientIP = request()->ip();
        $record = DB::connection('6060')
        ->table('users_copy')
        ->select('*')
        ->where('EmployeeCode',$req->user_data[0]['EmployeeCode'])
        ->get();

        if(count($record) > 0){
            return 'existing';
        }else{
            $admin_val = 0;
            $validCodes = ["647", "012", "003"];
            if (in_array($req->user_data[0]['DesignationCode'], $validCodes)) {
                $admin_val = 1; 
            }
            // if($req->user_data[0]['DesignationCode'] == "647" || $req->user_data[0]['DesignationCode'] == "012" || $req->user_data[0]['DesignationCode'] == "003"){
            //     $admin_val = 1; 
            // }
            
            // if(($req->user_data[0]['EmployeeCode'] == "36396") || ($req->user_data[0]['DepartmentCode'] == "147" && $req->user_data[0]['SectionCode'] == "00480") || ($req->user_data[0]['DepartmentCode'] == "145") ){
            $new_employee = strtolower($req->user_data[0]['EmployeeName']);
            return DB::connection('6060')
            ->table('users_copy')
            ->insert([
                'EmployeeCode' => $req->user_data[0]['EmployeeCode'],
                'EmployeeName' => ucwords($new_employee),
                'NickName' => $req->user_data[0]['NickName'],
                'DepartmentCode' => $req->user_data[0]['DepartmentCode'],
                'DepartmentName' => $req->user_data[0]['DepartmentName'],
                'SectionCode' => $req->user_data[0]['SectionCode'],
                'SectionName' => $req->user_data[0]['SectionName'],
                'TeamCode' => $req->user_data[0]['pcg_team_code'],
                'password' => $req->user_password,
                'ipaddress' => $clientIP,
                'admin' => $admin_val,
                'created_date' => date("Y-m-d H:i:s"),
                'created_by' => $clientIP,
            ]);
            // }else{
            //     return 'employee';
            // }
        }
    }
    
    public function updateUserData(Request $req){
        // return $req->update_table[0];
        // return intval($req->update_table[0]['TeamCode2']);

        $clientIP = request()->ip();
        $login_data = DB::connection('6060')
        ->table('users_copy as t1')
        ->select(
            't1.*',
            't2.name as version_name'
        )
        ->leftJoin('versions as t2','t1.version_id','=','t2.id')
        ->where('t1.EmployeeCode',$req->user_id)
        ->where('t1.password',$req->user_password)
        ->whereNull('t1.deleted_date')
        ->get();

        // $data = $login_data->get();
        if(count($login_data) > 0 && count($req->update_table) > 0){
            DB::connection('6060')
            ->table('users_copy')
            ->where('EmployeeCode',$login_data[0]->EmployeeCode)
            ->update([
                'ipaddress' => $clientIP,
                'DepartmentCode' => $req->update_table[0]['DepartmentCode'],
                'DepartmentName' => $req->update_table[0]['DepartmentName'],
                'SectionCode' => $req->update_table[0]['SectionCode'],
                'SectionName' => $req->update_table[0]['SectionName'],
            ]);
        }

        return $login_data;
       
        // if(count($login_data) > 0){
        //     if($login_data[0]->ipaddress == null){
        //         DB::connection('6060')
        //         ->table('users_copy')
        //         ->where('EmployeeCode',$login_data[0]->EmployeeCode)
        //         ->update([
        //             'ipaddress' => $clientIP,
        //         ]);
        //     }
        //     $login_data = $this->getLoginData($req->user_id,$req->user_password);
        //     return $login_data;
        // }else{
        //     return $login_data;
        // }
    }

    public function updateEmployees(){
        $get_users = DB::connection('6060')
        ->table('users_copy users')
        ->leftjoin('teams', 'users.TeamCode', '=', 'teams.pcg_team_code')
        ->select('users.EmployeeCode', 'teams.pcg_team_code','teams.name')
        ->get();

        try {
            foreach ($get_users as $key => $value) {
                // return $value->EmployeeCode;
                DB::connection('6060')
                ->table('users_copy')
                ->where('EmployeeCode',$value->EmployeeCode)
                ->update([
                    // 'DepartmentCode' => $value2->DepartmentCode,
                    // 'DepartmentName' => $value2->DepartmentName,
                    // 'SectionCode' => $value2->SectionCode,
                    // 'SectionName' => $value2->SectionName,
                    // 'pcg_team_code' => $value2->TeamCode,
                    'TeamCode' => $value->pcg_team_code,
                    'TeamName' => $value->name,
                    'updated_date' => date("Y-m-d H:i:s"),
                    // 'deleted_date' => $value2->RetiredDate
                ]);
            }
        } catch (\Exception $th) {
            return $th->getMessage();
        }
        
    }
}
