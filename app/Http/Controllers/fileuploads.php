<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class fileuploads extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Save file details to the database
            $new_file = new Files();
            $new_file->file_name = $file->getClientOriginalName();
            $new_file->file_size = $file->getSize();
            $new_file->file_extension = $file->getClientOriginalExtension(); 
            $new_file->save();
    

            $storedPath = $file->storeAs('docs', $new_file->file_name, 'public');
    
            return response()->json(['message' => 'File uploaded and saved correctly', 'stored_path' => $storedPath]);
        } else {
            return response()->json(['error' => 'File not found in request'], 400);
        }

        

    }



    public function retrieve_info($id){

        $new_file=Files::where('id',$id)->first();

        if($new_file){
            return response()->json([
                "status"=> 200,
                "file name"=>$new_file
            ],200);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>'file with this id not found '
            ],400);
        }

    }
    public function delete_info($id){

        
   
    $new_file = Files::find($id);

    if (!$new_file) {
        return response()->json([
            'status' => 400,
            'message' => 'Your file does not exist'
        ]);
    }

   $path='C:/laragon/www/hollow-dev-2/storage/app/public/docs/';

    $absolute_path =  $path . $new_file->file_name;

    if (file_exists($absolute_path)) {
        unlink($absolute_path);
        $new_file->delete();
        return response()->json([
            'status' => 200,
            'message' => 'File and its info deleted successfully'
        ]);
    } else {
        Log::error('File does not exist in storage: ' . $absolute_path);
        return response()->json([
            'status' => 400,
            'message' => 'File does not exist in storage',
            'path of it '=>$absolute_path
        ]);
    }
    
    }


    public function retrieve_file($id){

        $new_file=Files::where('id',$id)->first();

        $file_path='C:/laragon/www/hollow-dev-2/storage/app/public/docs/'. $new_file->file_name;
        if(file_exists($file_path))
        {
        return response()->download($file_path);
        }


        
    }
    public function update_info($id,$new_name){


        $new_file=Files::where('id',$id)->first();
        if($new_file){

            $file_extension=$new_file->file_extension;
            $file_path='C:\laragon\www\hollow-dev-2\storage\app\public\docs/'.$new_file->file_name.'.'. $file_extension;

            

            $newFilePath='C:\laragon\www\hollow-dev-2\storage\app\public\docs/'.$new_name.'.'. $file_extension;

            
            if(file_exists($file_path)){

                if(rename($file_path, $newFilePath)){

                    $new_file->file_name=$new_name;
                    $new_file->save();

                    return response()->json(
                    ['status'=>400,
                    'message'=>'the new file name is ',
                    'the_newpath'=>$newFilePath ]);}
            }else{


                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found',
                    'the file path'=>$file_path
                ], 404);

            }
            
        }else{
            return response()->json(['status'=>400,'message'=>'the given id is not exsit in Db']);
        }
    }
}
