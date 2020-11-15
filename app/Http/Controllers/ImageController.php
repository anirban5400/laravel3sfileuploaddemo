<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    
    public function create(){
        return view('images.create');
    }

    public function store(Request $request){

        // dd(count ( $request->file('imageFile') ),$request->file('imageFile'),$request);
        $get_response_array=[];
        $count=count ( $request->file('imageFile'));
        for($i=0;$i<$count;$i++){
            $path= $request->file('imageFile')[$i]->store('images','s3');
            // return "https://serviceseers-test.s3.us-east-2.amazonaws.com/".$path;
            // Storage::disk('s3')->setVisibility($path,'public');
            // Storage::disk('s3')->setVisibility($path,'private');
            $image= Image::create([
                'filename'=> basename($path),
                'url'=> Storage::disk('s3')->url($path)
            ]);
            $get_response_array[$i]=$image;
        }
        return $get_response_array;
        

    }
    public function show(Image $image){

        return Storage::disk('s3')->response('images/'.$image->filename);

    }

    public function presignedURL(){
        
            //Creating a presigned URL
        
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = "+10 minutes";

            $command = $client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => env('AWS_ACCESS_KEY_ID')
            ]);

            $request = $client->createPresignedRequest($command, $expiry);

            return (string) $request->getUri();
    }
    public function deleteImageS3(Request $request){
        $params = $request->all();

        // DB::table('table_image')->where('file_id',$file_id)->delete();
        // $filename= str_repeat(env('S3_URL'),'',$file_path);
        // $filename=explode('/',$filename);

        $filename=$params['filename'];

        // $mainImage="gallery_{$galleryId}/main/".$filename[count($filename)-1];
        // $mediumImage="gallery_{$galleryId}/medium/".$filename[count($filename)-1];
        // $thumbImage="gallery_{$galleryId}/thumb/".$filename[count($filename)-1];
        $mainImage="image/".$filename;

        $s3=Storage::disk('s3');
        $s3->delete($mainImage);
        // $s3->delete($mediumImage);
        // $s3->delete($thumbImage);
    }
}
