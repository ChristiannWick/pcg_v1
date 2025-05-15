<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
date_default_timezone_set('Asia/Manila');
use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
// ini_set('memory_limit', '256M');
use Illuminate\Support\Facades\File;

class PcgMonitoringController extends Controller
{
    public function monitoring_excel(Request $req){
        // return $req;
        $data = json_decode($req->data);
        return Excel::download(new MonitoringExport($data), 'data.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function openPDF(Request $req){

        if(strpos($req->directory,'\\hrdinfsv\information\\') !== false){
            // return 'hrd';
            $substringToRemove = '\\hrdinfsv\information\\';
            $directory = str_replace($substringToRemove, '', $req->directory);
            $filePath = $directory . '\\' . $req->filename;

            if (!Storage::disk('GETPDF')->exists($filePath)) {
                return response()->json(['message' => 'File not found.'], 404);
            }
           
            // $fileContent = Storage::disk('GETPDF')->get($filePath);
            // return response($fileContent, 200)->header('Content-Type', 'application/pdf');

            // Open the file using Laravel's storage system
            $handle = Storage::disk('GETPDF')->readStream($filePath);

            // Set the response headers
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            // Create a StreamedResponse with the callback function
            return new StreamedResponse(function () use ($handle) {
                while (!feof($handle)) {
                    echo fread($handle, 8192); // Output 8KB chunk from the file
                    flush(); // Flush the output buffer
                }
                fclose($handle);
            }, 200, $headers);
        }else{
            // return 'local';
            // $substringToRemove = '\\References\\';
            // $directory = str_replace($substringToRemove, '', $req->directory);

            $filePath = $req->directory. '\\' . $req->pdf_id;
            $fileContent = Storage::disk('public')->get($filePath);
            return response($fileContent, 200)->header('Content-Type', 'application/pdf');

            // $filePath = $req->directory . '\\' . $req->filename;

            // // Open the file using Laravel's storage system
            // $handle = Storage::disk('public')->readStream($filePath);

            // // Set the response headers
            // $headers = [
            //     'Content-Type' => 'application/pdf',
            // ];

            // // Create a StreamedResponse with the callback function
            // return new StreamedResponse(function () use ($handle) {
            //     while (!feof($handle)) {
            //         echo fread($handle, 8192); // Output 8KB chunk from the file
            //         flush(); // Flush the output buffer
            //     }
            //     fclose($handle);
            // }, 200, $headers);
        }
        
    }
    
    public function getFilename(Request $req){
        // return $req->filename;

        $filenames = explode('/', $req->filename); // Assuming $req->filename contains the filenames separated by slashes
        $results = [];

        foreach ($filenames as $filename) {
            $filename = trim($filename); //Strip whitespace (or other characters) from the beginning and end of a string

            $testing = DB::connection('10.168.64.66')
            ->select(DB::raw("
                SELECT * FROM 
                (
                    SELECT * FROM shiyoumanual_recent 
                    UNION ALL
                    SELECT * FROM rulebook_recent 
                    UNION ALL 
                    SELECT * FROM renrakuhyou_tsutatsu_recent 
                    UNION ALL 
                    SELECT * FROM hrdtsutatsu_recent data4
                ) whole_db
                WHERE 
                    whole_db.filename = '$filename.pdf'
                -- ORDER BY whole_db.filename ASC
            "));

            if (!empty($testing)) {
                $results = array_merge($results, $testing);
            }else {
                
                $result = DB::connection('10.168.64.66')
                ->select(DB::raw("
                    SELECT * FROM 
                    (
                        SELECT * FROM shiyoumanual_recent 
                        UNION ALL
                        SELECT * FROM rulebook_recent 
                        UNION ALL 
                        SELECT * FROM renrakuhyou_tsutatsu_recent 
                        UNION ALL 
                        SELECT * FROM hrdtsutatsu_recent data4
                    ) whole_db
                    WHERE 
                        whole_db.filename LIKE '%$filename%'
                        -- whole_db.filename like '$filename%'
                    -- ORDER BY whole_db.filename ASC
                "));

                // If $testing has no records, use $result
                $results = array_merge($results, $result);
            }
        }
        
        if(count($results) > 0){
            return $results;
        }else{
            return DB::connection('6060')
            ->select(DB::raw("
                SELECT * FROM pdf_list
                WHERE pdf_id LIKE '%".$req->filename."%'
                ORDER BY filename ASC
            "));
        }
    }

    public function getNotification(){
        $count = DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('approve_status','=', '1')
        ->whereNull('checked_by')
        ->whereNull('delete_date')
        ->count();

        return response()->json([
            'message' => 'for checking templates',
            'count' => $count
        ]);

    }

    public function indexMonitoring(Request $req){
        // return $req;
        
        $sort = "
        SELECT 
        t1.*, 
        t2.name as team_name,
        t3.pcg_counter
        FROM carte_pcg_copy t1
        join teams_copy t2
        on t1.pcg_team_code = t2.pcg_team_code
        left JOIN
        (
            SELECT 
            COUNT(*) AS pcg_counter, 
            code AS pcg_code2
            FROM use_carte_pcg
            GROUP BY pcg_code2
        ) t3
        ON t1.pcg_code = t3.pcg_code2
        WHERE t1.pcg_code is not null 
        AND t1.delete_date IS NULL";
        
        if($req->method != "All"){
            $sort .= " AND t1.pcg_method = '$req->method' ";
        }
        if($req->pcg != "All"){
            $sort .= " AND t1.pcg_type = '$req->pcg' ";
        }
        if($req->category != "All"){
            $sort .= " AND t1.pcg_category = '$req->category' ";
        }
        if($req->team != 1){
            $sort .= " AND t1.pcg_team_code = '$req->team' ";
        }
        if($req->status != "All"){
            if($req->status == "FC"){
                $sort .= " AND t1.approve_status = '1' AND t1.checked_by IS NULL ";
            }
            if($req->status == "C"){
                $sort .= " AND t1.approve_status = '1' AND t1.checked_by IS NOT NULL "; //checked
            }
            if($req->status == "A"){
                $sort .= " AND t1.approve_status = '2' "; //approved
            }
            if($req->status == "D"){
                $sort .= " AND t1.approve_status = '3' "; //disapproved
            }
        }
        if($req->dateFrom != null && $req->dateTo != null){
            $sort .= " AND t1.pcg_date BETWEEN '$req->dateFrom' AND '$req->dateTo 23:59:59' ";
        }

        if($req->status == "FU"){
            $sort .= " 
            ORDER BY t3.pcg_counter desc";
        }else{
            $sort .= " 
            ORDER BY t1.pcg_date DESC";
        }

        $final_arr = DB::connection('6060')
        ->select(DB::raw($sort));

        $sort .= " limit $req->startRecord,$req->selected_no_of_page";

        // $limit_plans = DB::connection('6060')
        // ->select(DB::raw($sort));

        return response()->json([
            // 'limit_plans' => $limit_plans,
            'data' => $final_arr,
            // 'sql' => $sort,
            // 'total_plans' => count($final_arr),
        ]);

    }

    public function add_pcg(Request $req){
        // return $req;
        // return $req['files'][0]['name'];
        // return $req['files'];
        $fileAtt = "";
        $fileName = "";
        $timestamp = date('ymdHis');
        if($req['files']){
            $data = [];
            $fileAtt = '\\\\References\\'.$req['files'][0]['column_name'];
            $fileAtt2 = 'References/'.$req['files'][0]['column_name'].'/'.$timestamp.'-'.$req['files'][0]['name'];
            $fileName = $req['files'][0]['name'];
            $fileSize = $req['files'][0]['size'];

            $dataB64 = $req['files'][0]['data'];
            list($type, $dataB64) = explode(';', $dataB64);
            list(, $dataB64)      = explode(',', $dataB64);
            $file_data = base64_decode($dataB64);
            $new_file_data = $file_data;
        }

        // foreach ($req['files'] as $key => $file) {
        //     $fileAttachmentPath = 'References/'.$file['column_name'].'/'.$timestamp.'-'.$file['name'];
        //     $fileAttachmentPath2 = '\\\\References\\'.$file['column_name'];

            
        //     // SAVING OF FILES TO PUBLIC STORAGE || AWS
        //     $dataB64 = $file['data'];
        //     list($type, $dataB64) = explode(';', $dataB64);
        //     list(, $dataB64)      = explode(',', $dataB64);
        //     $file_data = base64_decode($dataB64);

        //     $new_file_data = $file_data;
        //     $fileAtt = $fileAttachmentPath2;
        //     $fileAtt2 = $fileAttachmentPath;
        //     $fileName = $file['name'];
        //     $fileSize = $file['size'];
        // }

        $remarks = "";

        if(!$req->pcg_reference && !$req->pcg_reference_2 && !$req->pcg_reference_3 && !$req->pcg_reference_4 &&
        !$req->pcg_shio && !$req->pcg_hrd_memo && !$req->pcg_hrd_memo_2 && !$req->pcg_hrd_memo_3 && !$req->pcg_hrd_memo_4  && !$fileAtt){
            $remarks = 'Without Reference';
        }else{
            $remarks = 'With Reference';
        }

        $cleanedPcgEnglish = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $req->pcg_english);
        $cleanedPcgEnglish = str_replace('"', '', $cleanedPcgEnglish);

        $cleanedPcgJapanese = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $req->pcg_japanese);
        $cleanedPcgJapanese = str_replace('"', '', $cleanedPcgJapanese);


        DB::connection('6060')
        ->table('carte_pcg_copy')
        ->insert([
            'pcg_method' => $req->pcg_method,
            'pcg_category' => $req->pcg_category,
            'pcg_type' => $req->pcg_type,
            'pcg_code' => $req->pcg_code,
            // 'action_id' => $req->pcg_action_id,
            'pcg_english' =>  $cleanedPcgEnglish,
            'pcg_japanese' => $cleanedPcgJapanese,
            'pcg_reference' => $req->pcg_reference,
            'pcg_reference_2' => $req->pcg_reference_2, //rulebook above
            'pcg_reference_3' => $req->pcg_reference_3,  //rulebook below
            'pcg_reference_4' => $req->pcg_reference_4,
            'pcg_shio' => $req->pcg_shio,
            'pcg_hrd_memo' => $req->pcg_hrd_memo,
            'pcg_hrd_memo_2' => $req->pcg_hrd_memo_2,
            'pcg_hrd_memo_3' => $req->pcg_hrd_memo_3,
            'pcg_hrd_memo_4' => $req->pcg_hrd_memo_4,
            'pcg_translated_by' => $req->pcg_translated_by,
            'pcg_incharge_code' => $req->pcg_incharge_code,
            'pcg_incharge' => $req->pcg_incharge,
            'pcg_team_code' => $req->pcg_team_code,
            'approve_status' => "1" ,
            // 'remarks' => $req->remarks,
            'remarks' => $remarks, 
            // 'remarks_status' => $req->remarks_status, //Judgement
            'gc_attachment' => !$fileName?null:$timestamp.'-'.$fileName,
            'print' => 0
        
        ]);
        
        if($fileAtt != ""){
            // return 'walaanglaman';
            $envi = App::environment() == 'local' ? 'public' : 's3';
            Storage::disk($envi)->put($fileAtt2, $new_file_data);
            return DB::connection('6060')
            ->table('pdf_list')
            ->insert([
                'pdf_id' => $timestamp.'-'.$fileName,
                'directory' => $fileAtt,
                'filename' => $fileName,
                'size' => $fileSize,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $req->pcg_incharge
            ]);
        }

        

        

       
    }

    public function edit_pcg_attachment(Request $req){
        // return $req->id;
        $column_name = $req->input('column_name');
        DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            $column_name => $req->filename,
        ]);

        return response()->json([
            'messsage' => 'update success'
        ]);
    }
    
    public function edit_pcg(Request $req){
        // return $req;
       
        $fileName = "";
        $timestamp = date('ymdHis');
        if($req['files']){
            // return 'may attach';

            $att = $req->gc_attachment;
            $oldFilePath = 'References/GC ATTACHMENTS/' . $att;
            if (Storage::disk('public')->exists($oldFilePath)) {
                // echo 'test';
                
                Storage::disk('public')->delete($oldFilePath); // Delete the file if it exists
                
                $fileAtt = '\\\\References\\'.$req['files'][0]['column_name'];
                $fileAtt2 = 'References/'.$req['files'][0]['column_name'].'/'.$timestamp.'-'.$req['files'][0]['name'];
                $fileName = $req['files'][0]['name'];
                $fileSize = $req['files'][0]['size'];

                $dataB64 = $req['files'][0]['data'];
                list($type, $dataB64) = explode(';', $dataB64);
                list(, $dataB64)      = explode(',', $dataB64);
                $file_data = base64_decode($dataB64);
                $new_file_data = $file_data;

                if($fileAtt != ""){
                    // return 'walaanglaman';
                    $envi = App::environment() == 'local' ? 'public' : 's3';
                    Storage::disk($envi)->put($fileAtt2, $new_file_data);
                    DB::connection('6060')
                    ->table('pdf_list')
                    ->insert([
                        'pdf_id' => $timestamp.'-'.$fileName,
                        'directory' => $fileAtt,
                        'filename' => $fileName,
                        'size' => $fileSize,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $req->pcg_incharge
                    ]);
                }
            }
        }else{
            $fileName=0;
        }

    
        $remarks = "";

        if(!$req->pcg_reference && !$req->pcg_reference_2 && !$req->pcg_reference_3 && !$req->pcg_reference_4 &&
        !$req->pcg_shio && !$req->pcg_hrd_memo && !$req->pcg_hrd_memo_2 && !$req->pcg_hrd_memo_3 && !$req->pcg_hrd_memo_4 && !$fileName){
            $remarks = 'Without Reference';
        }else{
            $remarks = 'With Reference';
        }

        $cleanedPcgEnglish = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $req->pcg_english);
        $cleanedPcgEnglish = str_replace('"', '', $cleanedPcgEnglish);

        $cleanedPcgJapanese = preg_replace('/[\p{C}\p{Z}]+/u', ' ', $req->pcg_japanese);
        $cleanedPcgJapanese = str_replace('"', '', $cleanedPcgJapanese);

        return DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            'pcg_method' => $req->pcg_method,
            'pcg_category' => $req->pcg_category,
            'pcg_type' => $req->pcg_type,
            'pcg_code' => $req->pcg_code,
            // 'action_id' => $req->pcg_action_id,
            'pcg_english' => $cleanedPcgEnglish,
            'pcg_japanese' => $cleanedPcgJapanese,
            'pcg_reference' => $req->pcg_reference,
            'pcg_reference_2' => $req->pcg_reference_2,
            'pcg_reference_3' => $req->pcg_reference_3,
            'pcg_reference_4' => $req->pcg_reference_4,
            'pcg_shio' => $req->pcg_shio,
            'pcg_hrd_memo' => $req->pcg_hrd_memo,
            'pcg_hrd_memo_2' => $req->pcg_hrd_memo_2,
            'pcg_hrd_memo_3' => $req->pcg_hrd_memo_3,
            'pcg_hrd_memo_4' => $req->pcg_hrd_memo_4,
            'pcg_translated_by' => $req->pcg_translated_by,
            // 'pcg_incharge_code' => $req->pcg_incharge_code,
            // 'pcg_incharge' => $req->pcg_incharge,
            'remarks' => $remarks,
            // 'remarks_status' => $req->remarks_status,
            'gc_attachment' => !$fileName?$req->gc_attachment:$timestamp.'-'.$fileName,
         
        ]);
    }

    public function delete_pcg(Request $req){
        // return $req->id;
        $today = date("Y-m-d H:i:s");
        DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
          'delete_date' => $today
         
        ]);

        return response()->json([
            'message' => 'template deleted!'
        ]);
    }

    public function check_pcg(Request $req){
        // return $req->id;
        $today = date("Y-m-d H:i:s");
        return DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            'checked_by' => $req->checked_by,
            'checked_at' => $today
          
        ]);
    }

    public function approve_pcg(Request $req){
        // return $req->id;
        return DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            'approved_by' => $req->approved_by,
            'approved_at' => date("Y-m-d H:i:s"),
            'disapproved_by' => null,
            'disapproved_at' => null,
            'approve_status' => 2
         
        ]);
    }
    
    public function disapprove_pcg(Request $req){
        // return $req->id;
        return DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            'approved_by' => null,
            'approved_at' => null,
            'disapproved_by' => $req->disapproved_by,
            'disapproved_at' => date("Y-m-d H:i:s"),
            'approve_reason' => $req->reason,
            'approve_status' => 3
         
        ]);
    }
    
    public function reset_template_status(Request $req){
        // return $req->id;
        DB::connection('6060')
        ->table('carte_pcg_copy')
        ->where('id', $req->id)
        ->update([
            'approved_by' => null,
            'approved_at' => null,
            'checked_by' => null,
            'checked_at' => null,
            'disapproved_by' => null,
            'disapproved_at' => null,
            'approve_reason' => null,
            'approve_status' => 1
         
        ]);

        return response()->json([
            'message' => 'template reset'
        ]);
    }
    
    public function category(Request $req){
        // return $req;
        // if($req->method == null){
        //     $req->method = "All";
        // }
        if($req->method == "All"){
            $sort = "SELECT * FROM pcg_category ORDER BY category ASC";
        }else if($req->method == "Site"){
            $sort = "SELECT * FROM pcg_category WHERE method LIKE 'SITE' ORDER BY category ASC";
        }else if(
            $req->method == "Jikugumi" || 
            $req->method == "Wakugumi" ||
            $req->method == "Both" ||
            $req->method == "Tenjijou" ||
            $req->method == "I-Pallette_I-Smile"
        ){
            $sort = "SELECT * FROM pcg_category WHERE method LIKE 'CAD' ORDER BY category ASC";
        }else if($req->method == "HP_INT"){
            $sort = "SELECT * FROM pcg_category WHERE method LIKE 'HP INTERNAL' ORDER BY category ASC";
        }else if($req->method == "HP_EXT"){
            $sort = "SELECT * FROM pcg_category WHERE method LIKE 'HP EXTERNAL' ORDER BY category ASC";
        }

        return DB::connection('hrdapps31')
        ->select(DB::raw($sort));
    }

    public function code(Request $req){
        
        $pcgCategory = DB::connection('6060')
        ->select(DB::raw("
            SELECT pcg_code 
            FROM carte_pcg_copy
            WHERE pcg_code LIKE '$req->pcg_code%' 
            -- ORDER BY pcg_date DESC LIMIT 1
			ORDER BY CAST(SUBSTRING_INDEX(pcg_code, '-', -1) AS UNSIGNED) desc
			LIMIT 1;
        "));

        // $list = array();
        // foreach ($pcgCategory as $key => $value) {
        //     $list[] = $value->pcg_code;
        // }
        

        // if(count($list) > 0){
        //     $max = explode("-",$list[0]);
        //     $latest_code = $max[1] + 1;
        //     $count = str_pad($latest_code, 3, "0", STR_PAD_LEFT );
        //     return $max[0]."-".$count;
        // }else{
        //     $max = array();
        //     array_push($max,$req->pcg_code);
        //     return $max[0]."-"."001";
        // }
        
        if ($pcgCategory) {
            $parts = explode("-", $pcgCategory[0]->pcg_code);
            $latestCode = (int) $parts[1] + 1;
            $count = str_pad($latestCode, 3, "0", STR_PAD_LEFT);
            return $parts[0] . "-" . $count;
        } else {
            return $req->pcg_code . "-001";
        }



        // $category = $req->category;
        // $method = $req->method;

        // if($method == "Jikugumi" || $method == "Wakugumi" || $method == "Both" || $method == "Tenjijou" || $method == "I-Pallette_I-Smile"){
        //     $method = "CAD";
        // }else if($method == "Site"){
        //     $method = "SITE";
        // }else if($method == "HP_INT"){
        //     $method = "HP INTERNAL";
        // }else if($method == "HP_EXT"){
        //     $method = "HP EXTERNAL";
        // }
        // // return $method;
        
        // $pcgCategory = DB::connection('hrdapps31')
        // ->select(DB::raw("
        //     SELECT * FROM pcg_category
        //     WHERE 
        //     method LIKE '%".$method."%' AND	category LIKE '%".$category."%'
        // "));

        // $pcgCode = array();

        // foreach ($pcgCategory as $key => $value) {
        //     $pcgCode = $value->code;
        // }
        // // return $pcgCode;
        // $check = DB::connection('hrdapps31')
        // ->select(DB::raw("
        //     SELECT * FROM pcg_new
        //     WHERE 
        //         pcg_code LIKE '%".$pcgCode."%' 
            
        //     ORDER BY id DESC LIMIT 1
        // "));

        // $list = array();
        // foreach ($check as $key => $value) {
        //     $list[] = $value->pcg_code;
        // }

        // if(count($list) > 0){
        //     $max = explode("-",$list[0]);
        //     // print_r($max);
        //     $latest_code = $max[1] + 1;
        //     // echo($latest_code);
        //     $count = str_pad($latest_code, 4, "0", STR_PAD_LEFT );
        //     return $max[0]."-".$count;
        // }

    }

}
