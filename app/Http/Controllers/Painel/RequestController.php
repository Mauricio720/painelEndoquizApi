<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\ImagesVideo;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function getImageVideo($id){
        $imageVideo=ImagesVideo::where('id',$id)->first();
        if($imageVideo != null){
            return json_encode($imageVideo);
        }
    }
}
