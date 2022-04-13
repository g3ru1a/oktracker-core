<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class ImageUploadController extends Controller
{

    /**
     * @param string        $folder
     * @param UploadedFile  $image
     * 
     * @return string Path To Image
     */
    public static function uploadUserProfilePicture($user, $image): string
    {
        return self::upload('profile_pictures/'.$user->id, $image);
    }

    /**
     * @param string        $folder
     * @param UploadedFile  $image
     * @param bool          $original_extension 
     * @param string|null   $extension
     * @param integer       $quality
     * 
     * @return string Path To Image
     */
    public static function upload($folder, $image, $original_extension = true, $extension = null, $quality = 90): string
    {
        if($original_extension == false && $extension == null){
            $original_extension = true;
        }
        $ext = '.' . $original_extension ? $image->getClientOriginalExtension() : $extension;
        $filename = self::generateRandomString(20) . $ext;
        $filename = preg_replace('/[^A-Za-z.]+/', '', $filename);

        $img = Image::make($image)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode($ext, $quality);

        $path = "$folder/$filename";
        Storage::put($path, $img);
        return '/'.$path;
    }

    private static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
