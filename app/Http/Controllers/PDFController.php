<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        set_time_limit(0);

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
          
        $pdf = PDF::loadView('myPDF', $data);
        
          return view('myPDF');
        
        return $pdf->stream();

        // return $pdf->download('itsolutionstuff.pdf');
    }

    // public function generatePDF()
    // {
    //     $url = 'http://agencydashboard.io/';

    //    // Create a stream
    //    $opts = [
    //        "http" => [
    //            "method" => "GET",
    //            "header" => "Host: agencydashboard.io\r\n"
    //                . "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:71.0) Gecko/20100101 Firefox/71.0\r\n"
    //                . "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
    //                . "Accept-Language: en-US,en;q=0.5\r\n"
    //                . "Accept-Encoding: gzip, deflate, br\r\n"
    //        ],
    //    ];

    //    $context = stream_context_create($opts);
    //    $contents = file_get_contents($url);
    //    // $data = file_get_contents($url, false, $context);

    //    \Storage::disk('public')->put('filename.pdf', $contents);

    //    return 'OK';
    // }
}