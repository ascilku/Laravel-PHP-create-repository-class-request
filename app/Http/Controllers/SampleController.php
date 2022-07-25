<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Requests\SampelRequest as Sampel;
use App\Http\Requests\SampleRequest;
use App\Helpers\ResponseFormatter;
use App\Http\Repositories\SampelRepository;



class SampleController extends Controller
{
    private $sampelRepository;
    public function __construct()
    {
        $this->sampelRepository = new SampelRepository();
    }


    //
    public function Index(SampleRequest $request){

        // $this->validate($request, [
        //     'sample'               => 'required|max:20' ,
        // ]); 
        $credentials = request(['name', 'email', 'password']);
        // echo $credentials['name'];
        $data = $this->sampelRepository->getDataPeymentMembers($credentials);
        // $data = ['kurni', 'hamsah', 'sandi'];
        return ResponseFormatter::responFormatter('succes', 200, 'get berhasil', $data);
           
        
            // if($sample == ""){
            //     return ResponseFormatter::responFormatter('succes', 200, 'get berhasil', $data);
            // }else{
            //     return ResponseFormatter::responFormatter('succes', 200, 'get berhasil', $data);
            // }
        
        

    } 
}
