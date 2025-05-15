<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Manila');
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use TCPDF;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class PcgReportController extends Controller
{

    public function getFilename2Old(Request $req){
        ini_set('memory_limit', '256M');
        // dd($req);
        $arr1 = [];
        foreach ($req->all() as $value) {
            $property = key($value);
            if (isset($value[$property])) {
                $editRefValue = $value[$property]; // Access the value of the property
            } else {
                // Handle the case where the property value is not present
                continue; // Skip the iteration
            }
            $sql = "
                SELECT * FROM 
                (
                    SELECT * FROM shiyoumanual_recent 
                    UNION ALL
                    SELECT * FROM rulebook_recent 
                    UNION ALL 
                    SELECT * FROM renrakuhyou_tsutatsu_recent 
                    UNION ALL 
                    SELECT * FROM hrdtsutatsu_recent data4
                )whole_db
                WHERE 
                    -- whole_db.filename LIKE '%".$editRefValue ."%'
                    whole_db.filename like '%$editRefValue%'
                -- ORDER BY whole_db.filename ASC
                ORDER BY whole_db.date_updated desc
                LIMIT 1
            ";

            $sql2 = "
                SELECT * FROM pdf_list
                WHERE pdf_id LIKE '%".$editRefValue."%'
                ORDER BY filename ASC
            ";

            $cad = DB::connection('10.168.64.66')
            ->select(DB::raw($sql));
            $attachment = DB::connection('6060')
            ->select(DB::raw($sql2));


            if(count($cad) > 0){
                array_push($arr1, $cad);
            }
            if(count($attachment) >0){
                array_push($arr1, $attachment);
            }
        }
        $flattenedArray = array_merge(...$arr1);
        // return $flattenedArray;

        $fileBuffers = [];
        $pdfBuffers = [];
        foreach ($flattenedArray as $key => $path) {
            $substringToRemove = '\\hrdinfsv\information\\';
            $directory = str_replace($substringToRemove, '', $path->directory);
            $filePath = $directory. '\\' . $path->filename;
            $filePath2 = $directory;
            if (Storage::disk('GETPDF')->exists($filePath)) {
                $buff = Storage::disk('GETPDF')->get($filePath);
                $fileBuffers[] = base64_encode($buff);
            }
            if (Storage::disk('public')->exists($filePath2)) {
                $buff = Storage::disk('public')->get($directory. '\\' . $path->pdf_id);
                $fileBuffers[] = base64_encode($buff);
            }
        }

        return response()->json([
            'fileBuffer' => $fileBuffers,
        ]);
        
    }

    public function getFilename2(Request $req){
        ini_set('memory_limit', '256M');
        // dd($req);
        $arr1 = [];
        foreach($req->all() as $value){
            $keys = array_keys($value);

            if (count($keys) >= 2) {
                // Get the first key and value
                $firstKey = $keys[0];
                $firstValue = $value[$firstKey];
        
                // Get the second key and value
                $secondKey = $keys[1];
                $secondValue = $value[$secondKey];

                $sql = "
                    SELECT * FROM 
                    (
                        SELECT * FROM shiyoumanual_recent 
                        UNION ALL
                        SELECT * FROM rulebook_recent 
                        UNION ALL 
                        SELECT * FROM renrakuhyou_tsutatsu_recent 
                        UNION ALL 
                        SELECT * FROM hrdtsutatsu_recent data4
                    )whole_db
                    WHERE 
                        -- whole_db.filename LIKE '%".$firstValue ."%'
                        whole_db.filename like '%$firstValue%'
                    -- ORDER BY whole_db.filename ASC
                    ORDER BY whole_db.date_updated desc
                    LIMIT 1
                ";

                $sql2 = "
                    SELECT * FROM pdf_list
                    WHERE pdf_id LIKE '%".$firstValue."%'
                    ORDER BY filename ASC
                ";

                $cad = DB::connection('10.168.64.66')
                ->select(DB::raw($sql));

                $attachment = DB::connection('6060')
                ->select(DB::raw($sql2));

                if(count($cad) > 0){
                    // Assuming $cad is an array of objects, we take the first object
                    $firstObject = $cad[0];
                    // Add the secondValue to the first object
                    $firstObject->pages = $secondValue;
                    // Push the modified object into $arr1
                    array_push($arr1, $firstObject);
                    
                }

                if(count($attachment) >0){
                    // Assuming $cad is an array of objects, we take the first object
                    $firstObject = $attachment[0];
                    // Add the secondValue to the first object
                    $firstObject->pages = $secondValue;
                    // Push the modified object into $arr1
                    array_push($arr1, $firstObject);

                }

            }

            // return $arr1;
        }
        // return $arr1;

        $fileBuffers = [];
        $pdf_pages = [];
        foreach ($arr1 as $key => $path) {
            $substringToRemove = '\\hrdinfsv\information\\';
            $directory = str_replace($substringToRemove, '', $path->directory);
            $filePath = $directory . '\\' . $path->filename;
            $filePath2 = $directory;

            // Check if the file exists
            if (Storage::disk('GETPDF')->exists($filePath)) {
                // Get the total number of pages in the PDF
                $buff = Storage::disk('GETPDF')->get($filePath);
                $fileBuffers[] = base64_encode($buff);
                $pdf_pages[] = $path->pages;
                
                Log::info("Processing file: {$filePath}, Size: " . strlen($buff));
                // If pages are specified, extract them; otherwise, use the entire PDF
                // if (!empty($path->pages)) {
                //     // Extract specified pages
                //     $pdfOutput = $this->extractPagesFromPdf($buff, $path->pages);
                //     $fileBuffers[] = base64_encode($pdfOutput);
                // } else {
                //     // Use the entire PDF
                //     $fileBuffers[] = base64_encode($buff);
                // }
            }else{
                Log::error("File does not exist: {$filePath}");
            }

            if (Storage::disk('public')->exists($filePath2)) {
                $buff = Storage::disk('public')->get($directory. '\\' . $path->pdf_id);
                $fileBuffers[] = base64_encode($buff);
            }
            
        }

        return response()->json([
            'fileBuffer' => $fileBuffers,
            'fileBufferPages' => $pdf_pages,
        ]);
        
    }

    public function extractPagesFromPdf($pdfContent, $pagesString)
    {
        // Create a new PDF parser instance
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);
    
        // Create a new TCPDF instance
        $tcpdf = new TCPDF();
    
        // Set document information
        $tcpdf->SetCreator(PDF_CREATOR);
        $tcpdf->SetAuthor('Your Name');
        $tcpdf->SetTitle('Combined PDF Document');
        $tcpdf->SetSubject('PDF Extraction');
        $tcpdf->SetKeywords('TCPDF, PDF, extraction');
    
        // Get the total number of pages
        $pages = $pdf->getPages();
        $pageCount = count($pages);
    
        // Parse the pages string (e.g., "1-2", "1,2", "1,2,3-6")
        $pagesToExtract = $this->parsePagesString($pagesString, $pageCount);
    
        // Loop through the pages to extract
        foreach ($pagesToExtract as $pageNumber) {
            if ($pageNumber > 0 && $pageNumber <= $pageCount) {
                // Add a new page to the TCPDF
                $tcpdf->AddPage();
                // Get the content of the page
                $page = $pages[$pageNumber - 1];
                $tcpdf->writeHTML($page->getText());
            }
        }
    
        // Output the new PDF to a string
        return $tcpdf->Output('S'); // 'S' means return the document as a string
    }

    public function parsePagesString($pagesString, $pageCount){
        $pagesToExtract = [];
    
        // Split the string by commas
        $parts = explode(',', $pagesString);
    
        foreach ($parts as $part) {
            if (strpos($part, '-') !== false) {
                // Handle ranges (e.g., "1-3")
                list($start, $end) = explode('-', $part);
                $start = (int)$start;
                $end = (int)$end;
    
                // Ensure the range is valid
                if ($start > 0 && $end <= $pageCount && $start <= $end) {
                    $pagesToExtract = array_merge($pagesToExtract, range($start, $end));
                }
            } else {
                // Handle single pages (e.g., "1")
                $pageNumber = (int)$part;
                if ($pageNumber > 0 && $pageNumber <= $pageCount) {
                    $pagesToExtract[] = $pageNumber;
                }
            }
        }
    
        // Remove duplicates and sort the array
        $pagesToExtract = array_unique($pagesToExtract);
        sort($pagesToExtract);
    
        return $pagesToExtract;
    }

    public function report_excel(Request $req){
        // return $req;
        $data = json_decode($req->data);
        return Excel::download(new ReportExport($data), 'data.xls', \Maatwebsite\Excel\Excel::XLS);
    }


    public function indexReport(Request $req)
    {
        return $sql = DB::connection('hrdapps31')
        ->table('use_pcg')
        ->select('*')
        ->whereNotNull('id')
        ->orderBy('date','desc')
        // ->where()
        // ->orderBy('checked_at','desc')
        ->get();
        
        
    }

    public function delete_report_pcg(Request $req){
        // return $req;
        DB::connection('6060')
        ->table('use_carte_pcg')
        ->where('id',$req->id)
        ->delete();

        return response()->json([
            'message' => 'item deleted!'
        ]);
    }

    public function insertReport(Request $req){
        // return $req;
        // return gettype(array($req));
        foreach ($req->all() as $key => $value) {
            DB::connection('6060')
            ->table('use_carte_pcg')
            ->insert([
                'Employee_No' => $value['Employee_No'] ?? null,
                'Employee_Name' => $value['pcg_incharge'] ?? null,
                'pcg_category' => $value['pcg_category'] ?? null,
                'code' => $value['pcg_code'] ?? null,
                'action_id' => $value['action_id'] ?? null,
                'control_number' => $value['control_number'] ?? null,
                'revision' => $value['revision'] ?? null,
                'house_type' => $value['house_type'] ?? null,
                'pcg_type' => $value['pcg_type'] ?? null,
                'planner_kanji' => $value['planner_kanji'] ?? null,
                'planner_roma' => $value['planner_roma'] ?? null,
                'planner_code' => $value['planner_code'] ?? null,
                'planner_branch_code' => $value['planner_branch_code'] ?? null,
                'planner_branch_name' => $value['planner_branch_name'] ?? null,
                'salesman_kanji' => $value['salesman_kanji'] ?? null,
                'salesman_roma' => $value['salesman_roma'] ?? null,
                'salesman_code' => $value['salesman_code'] ?? null,
                'salesman_branch_code' => $value['salesman_branch_code'] ?? null,
                'salesman_branch_name' => $value['salesman_branch_name'] ?? null,
                'remarks' => $value['remarks'] ?? null,
                'judgement' => $value['judgement'] ?? null,
                'english' => $value['pcg_english'] ?? null,
                'japanese' => $value['pcg_japanese'] ?? null,
                'date' => date('Y-m-d H:i:s'),
                'reference' => $value['gc_attachment'] ?? null,
                'edit_ref1' => $value['pcg_reference'] ?? null,
                'edit_ref1_pages' => $value['pcg_reference_page_cart'] ?? null,
                'edit_ref2' => $value['pcg_reference_2'] ?? null,
                'edit_ref2_pages' => $value['pcg_reference_2_page_cart'] ?? null,
                'edit_ref3' => $value['pcg_reference_3'] ?? null,
                'edit_ref3_pages' => $value['pcg_reference_3_page_cart'] ?? null,
                'edit_ref4' => $value['pcg_reference_4'] ?? null,
                'edit_ref4_pages' => $value['pcg_reference_4_page_cart'] ?? null,
                'edit_memo1' => $value['pcg_hrd_memo'] ?? null,
                'edit_memo1_pages' => $value['pcg_hrd_memo_page_cart'] ?? null,
                'edit_memo2' => $value['pcg_hrd_memo_2'] ?? null,
                'edit_memo2_pages' => $value['pcg_hrd_memo_2_page_cart'] ?? null,
                'edit_shio' => $value['pcg_shio'] ?? null,
                'edit_shio_pages' => $value['pcg_shio_page_cart'] ?? null,
                'edit_memo3' => $value['pcg_hrd_memo_3'] ?? null,
                'edit_memo3_pages' => $value['pcg_hrd_memo_3_page_cart'] ?? null,
                'edit_memo4' => $value['pcg_hrd_memo_4'] ?? null,
                'edit_memo4_pages' => $value['pcg_hrd_memo_4_page_cart'] ?? null,
                'approved_by' => $value['approved_by'] ?? null,
            ]);
        }
        
        return response()->json([
            'message' => 'inserted success'
        ]);
        
    }

    public function getReport(Request $req){
        // return $req;
        $query = DB::connection('6060')
        ->select(DB::raw("
            SELECT 
            ucp.id,
            ucp.Employee_No,
            ucp.pcg_category,
            ucp.code,
            ucp.action_id,
            ucp.control_number,
            ucp.revision,
            ucp.pcg_type,
            ucp.judgement, 
            ucp.english,
            ucp.japanese,
            ucp.date,
            ucp.reference,
            ucp.edit_ref1,
            ucp.edit_ref2,
            ucp.edit_ref3,
            ucp.edit_ref4,
            ucp.edit_memo1,
            ucp.edit_memo2,
            ucp.edit_shio,
            ucp.edit_memo3,
            ucp.edit_memo4,
            ucp.edit_ref1_pages,
            ucp.edit_ref2_pages,
            ucp.edit_ref3_pages,
            ucp.edit_ref4_pages,
            ucp.edit_memo1_pages,
            ucp.edit_memo2_pages,
            ucp.edit_shio_pages,
            ucp.edit_memo3_pages,
            ucp.edit_memo4_pages,
            t2.english as action_english,
            t2.japanese action_japanese,
            ucp.approved_by
            
            FROM use_carte_pcg ucp
            left join actions t2 
            on ucp.action_id = t2.action_id
            WHERE ucp.date between '$req->dateFrom 00:00:00' and '$req->dateTo 23:59:59' 
            -- order by date desc
        "));

        return $query;
    }

    public function getJapanEmployees(Request $req){
        // return $req;
        return DB::connection('HRDSQL')
        ->select(DB::raw("
            SELECT e1.EmployeeName AS plannerName, e1.EmployeeNameRoma AS plannerNameRomanji, c.PlannerCode, e1.AssignedExhibitionCode AS plannerBranchCode, ce.ContractExhibitionName AS plannerBranchName,
            e2.EmployeeName AS salesmanName, e2.EmployeeNameRoma AS salesmanNameRomanji, c.SalesmanCode, e2.AssignedExhibitionCode AS salesmanBranchCode, ce2.ContractExhibitionName AS salesmanBranchName
            FROM dbo.Constructions c
            left JOIN dbo.Employees e1
            ON c.PlannerCode = e1.EmployeeCode
            left JOIN dbo.Employees e2
            ON c.SalesmanCode = e2.EmployeeCode
            left JOIN  dbo.ContractExhibitions ce
            ON e1.AssignedExhibitionCode = ce.ContractExhibitionCode
            left JOIN  dbo.ContractExhibitions ce2
            ON e2.AssignedExhibitionCode = ce2.ContractExhibitionCode
            where c.ConstructionCode = '".$req->ConstructionCode."'
        "));
    }
}
