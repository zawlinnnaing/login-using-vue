<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2019-01-18
 * Time: 14:01
 */

namespace App\Common;


use Illuminate\Support\Facades\File;

trait CommonFunctions
{
    /**
     * @param $file
     * @param $directoryName
     * @param null $imageName
     * @return mixed
     */
    public function uploadImage($file, $directoryName, $imageName = null)
    {
        if (empty($imageName)) {
            $fileName = $file->getClientOriginName();
        } else {
            $fileName = strtolower($imageName);
            $fileName = explode(' ', $fileName);
            $fileName = implode('_', $fileName);
            $fileName = time() .'_'. $fileName . '.jpg';
        }
//        if (Storage::size($file) > 1000000) {
//            throw new FileSizeTooLargeException();
//        }
        $directory = public_path() . '/storage/' . $directoryName;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $path = $directory . '/' . $fileName;

        File::put($path, $file);
        return $fileName;
    }

    /**
     * @param $fileName
     * @param $directoryName
     */
    public function deleteImage($fileName, $directoryName)
    {
        $path = public_path() . '/storage/' . $directoryName . '/' . $fileName;
        if (file_exists($path)) {
            File::delete($path);
        }

    }

    /**
     * @param $encodedString
     * @return bool|string
     */
    public function decodeImage($encodedString)
    {
        $base64Image = explode(',', $encodedString);
        $image = base64_decode($base64Image[1]);
        return $image;
    }

}