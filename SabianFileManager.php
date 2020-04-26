<?php
/**
 * A file upload manager for laravel
 *
 * @author bryosabian
 */

use Storage;

class SabianFileManager {

    /**
     * Converts and stores the file from base 64
     * @param String $base64String The base 64 string
     * @param String $folder The folder where the file will be stored (Without the trailing hash)
     * @param String $newName The new file name. If not specified, a default file name will be used (If specified, without the mimetype)
     * @param String $fileType The file type without the dot (Defaults to png)
     * @param String $link The drive link for the storage disk (Defaults to public)
     * @return Array Key value pair of path and url
     * @throws SabianException
     */
    public static function getFileFromBase64($base64String, $folder = null, $newName = null, $fileType = "png", $link = "public") {
        if (is_null($base64String)) {
            return null;
        }
        if (is_null($folder)) {
            $folder = "uncategorized";
        }
        $folder .= "/";

        if (!is_null($newName)) {
            $newfile = implode(".", [$newName, $fileType]);
        } else {
            $newfile = sprintf("%s_%s", time(), uniqid());
            $newfile = sprintf("%s.%s", $newfile, $fileType);
        }
        $bFile = $base64String;
        $bFile = str_replace('data:image/png;base64,', '', $bFile);
        $bFile = str_replace('data:image/jpeg;base64,', '', $bFile);
        $bFile = str_replace(' ', '+', $bFile);
        $bFile = base64_decode($bFile);

        $newfile = $folder . $newfile;
        $disk = Storage::disk($link);
        $disk->put($newfile, $bFile);
        $path = $disk->path($newfile);
        $url = $disk->url($newfile);
        return ["url" => $url, "path" => $path];
    }

    /**
     * Converts and stores the image from base 64
     * @param String $base64String The base 64 string
     * @param String $folder The folder where the image will be stored (Without trailing hash)
     * @param String $newName The new file name. If not specified, a default file name will be used (File name should be without the MimeType)
     * @param String $fileType The file type without the dot (Defaults to png)
     * @return Array Key value pair of path and url
     * @throws SabianException
     */
    public static function getImageFromBase64($base64String, $folder = "images", $newName = null, $fileType = "png") {
        if (is_null($folder)) {
            $folder = "images";
        }
        if (is_null($fileType)) {
            $fileType = "png";
        }
        return self::getFileFromBase64($base64String, $folder, $newName, $fileType);
    }

    /**
     * Gets audio from base64 and stores or replaces the audio if it exists on the specified folder
     * @param string $base64String
     * @param string $folder The folder where the audio will be stored(Without trailing hash)
     * @param string $newName The new name of the audio file
     * @return Array Key value pair of path and url
     * @throws SabianException
     */
    public static function getAudioFromBase64($base64String, $folder = "audio", $newName = null) {
        if (is_null($folder)) {
            $folder = "audio";
        }
        return self::getFileFromBase64($base64String, $folder, $newName, "mp3");
    }

}
