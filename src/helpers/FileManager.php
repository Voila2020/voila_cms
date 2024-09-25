<?php

use Illuminate\Support\Facades\DB;


class FileManager
{

    public static function edit_alt_text($path,$name,$alt){
     $alt = addslashes(strip_tags($alt));   
     $res = DB::table('files_alternate_texts')->where("file_path",'like',"$path")->first();
     if($res){
          DB::table('files_alternate_texts')->where("id",'=',$res->id)->update([
               'alt_text'=> "$alt"
          ]);
     }else{
          DB::table('files_alternate_texts')->insert([
               'file_path' => "$path",
               'alt_text'=> "$alt"
          ]);
     }
     return true;
    }

    public static function get_file_alt_text($path){
        
        $res = DB::table('files_alternate_texts')->where("file_path",'like',"$path")->first();
        if($res){
            return stripcslashes($res->alt_text) ?? "";
        }else{
            return "";
        }
    }
}
