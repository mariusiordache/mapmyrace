<?php

/**
 * @author http://www.myappsnippet.com
 * @desc Work with files and folders like zip files, icons and etc in PHP
 * @version 1.0.0
 * @example $myVar = new fileManagement(); $myVar->functions($var1,$var2,...);
 */
class filemanagement {

    /**
     * this function creates a zip file from a folder
     * @param the name of the folder you want to zip it: $folderName
     * @param the name of zip file you want to create: $zipFileName
     * @return true or false
     * @example  if($myVar->create_zip("yourFolder","YourZipNameFile"))
     * 			{
     * 			    echo "folder has been ziped";
     * 			}
     * 			else
     * 			{
     * 			    echo "can not zip this folder";
     * 			}
     */
    public function create_zip($folderName, $zipFileName) {
        $zip = new ZipArchive();

        $zipFileName = strstr($zipFileName, ".zip") !== false ? $zipFileName : $zipFileName . ".zip";
        $zip_archive = $zip->open($zipFileName, ZIPARCHIVE::CREATE);

        if (is_dir($folderName)) {

            if ($zip_archive === true) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderName));
                foreach ($iterator as $key => $value) {
                    if (!is_dir(realpath($key))) {
                        $zip->addFile(realpath($key), str_replace($folderName, "", $key));
                    }
                }
            }
        } else if (is_file($folderName)) {
            $zip->addFile(realpath($folderName), str_replace(dirname($folderName), "", $folderName));
        } else {
            return false;
        }

        $zip->close();
        if (file_exists($zipFileName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * this function extracts a zip file
     * @param your zip file: $zipFileName
     * @param Location in which you want to extract: $pasteLocation
     * @return true or false
     * @example if($myVar->extract_zip("YourZipNameFile.zip","YourPasteLocation"))
     * 			{
     * 			    echo "folder has been extract";
     * 			}
     * 			else
     * 			{
     * 			    echo "can not extract folder";
     * 			}
     */
    public function extract_zip($zipFileName, $pasteLocation = null) {

        if ($pasteLocation === null) {

            $filename = str_replace('.zip', '', basename($zipFileName));
            $filepath = dirname($zipFileName);

            $pasteLocation = $filepath . '/' . $filename . '-' . md5(microtime());
        }

        if (!is_dir($pasteLocation)) {
            mkdir($pasteLocation, 0777, true);
        }
        $zip = new ZipArchive();
        if ($zip->open($zipFileName) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $zip->extractTo($pasteLocation, array($zip->getNameIndex($i)));
            }
            $zip->close();
            if (is_dir($pasteLocation) or is_file($pasteLocation)) {
                return $pasteLocation;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * this function gets realpath of a file in the host server(NOT WORKING ON LOCALHOST)
     * @param address of your file: $filePath
     * @example $myVar->_realpath("/yourFolder/yourFile");
     * @return returns a string: "http://yourdomain.com/yourFolder/yourFile" or "can not get realpath"
     */
    public function _realpath($filePath) {
        $hostname = $_SERVER['HTTP_HOST'];
        $ip = $_SERVER['SERVER_ADDR'];
        $realpath_file = realpath($filePath);
        $haystack = $realpath_file;
        $needle = $hostname;
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            $haystack = $realpath_file;
            $needle = $ip;
            $pos = strpos($haystack, $needle);
            if ($pos === false) {
                return "can not get realpath";
            } else {
                $exp = explode("/", $realpath_file);
                foreach ($exp as $key => $value) {
                    if ($exp[$key] == $ip) {
                        $imp = implode("/", $exp);
                        break;
                    } else {
                        unset($exp[$key]);
                    }
                }
                return "http://" . $imp;
            }
        } else {
            $exp = explode("/", $realpath_file);
            foreach ($exp as $key => $value) {
                if ($exp[$key] == $hostname) {
                    $imp = implode("/", $exp);
                    break;
                } else {
                    unset($exp[$key]);
                }
            }
            return "http://" . $imp;
        }
    }

    /**
     * 
     * this function copies a directory to another directory
     * @param Your source directory: $source
     * @param target location directory: $destination
     * @return No return
     */
    public function copy_directory($source, $destination) {
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (FALSE !== ( $readdirectory = $directory->read() )) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }
                $PathDir = $source . '/' . $readdirectory;
                if (is_dir($PathDir)) {
                    self::copy_directory($PathDir, $destination . '/' . $readdirectory);
                    continue;
                }
                copy($PathDir, $destination . '/' . $readdirectory);
            }

            $directory->close();
        } else {
            copy($source, $destination);
        }
    }

    /**
     * 
     * this function deletes a directory
     * @param address of your directory: $directory
     * @return true or false
     */
    public function recursiveDelete($directory) {
        // if the path is not valid or is not a directory ...
        if (!file_exists($directory) || !is_dir($directory)) {
            return false;
        } elseif (!is_readable($directory)) {// ... if the path is not readable
            return false;
        } else { // ... else if the path is readable
            // open the directory
            $handle = opendir($directory);

            // and scan through the items inside
            while (false !== ($item = readdir($handle))) {
                // if the filepointer is not the current directory
                // or the parent directory
                if ($item != '.' && $item != '..') {
                    // we build the new path to delete
                    $path = $directory . '/' . $item;

                    // if the new path is a directory
                    if (is_dir($path)) {
                        // we call this function with the new path
                        self::recursiveDelete($path);
                    } else { // if the new path is a file
                        // remove the file
                        unlink($path);
                    }
                }
            }

            // close the directory
            closedir($handle);

            // try to delete the now empty directory
            if (@!rmdir($directory)) {
                return false;
            }

            return true;
        }
    }

    /** this function computes the size of a given directory
     * @return int size of directory in bytes
     * */
    public function dir_size($path) {
        $total_size = 0;
        $files = scandir($path);
        $cleanPath = rtrim($path, '/') . '/';

        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                $currentFile = $cleanPath . $file;
                if (is_dir($currentFile)) {
                    $size = $this->dir_size($currentFile);
                    $total_size += $size;
                } else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }
        }

        return $total_size;
    }

    /**
     * this function creates android icons from a png icon(512*512)
     * @param address of folder in which you want icons to be created: $dir
     * @param address of your icon that you want to change size: $iconPath
     * @return true or false
     * @example $myVar->createAndroidIcons("yourFolderForNewIcons","yourFolder/yourIcon.png");
     */
    public function createAndroidIcons($dir, $iconPath) {
        if (!is_dir($dir)) {
            return false;
            exit();
        } else {
            $type = getimagesize($iconPath);
            if ($type['mime'] == "image/png") {
                // icon_47
                $icon_47 = self::makeThumbnail($iconPath, $dir . '/icon_48' . '.png', 48, 48, 9);
                // icon_57
                $icon_57 = self::makeThumbnail($iconPath, $dir . '/icon_57' . '.png', 57, 57, 9);
                // icon_72
                $icon_72 = self::makeThumbnail($iconPath, $dir . '/icon_72' . '.png', 72, 72, 9);
                // icon_114
                $icon_114 = self::makeThumbnail($iconPath, $dir . '/icon_114' . '.png', 114, 114, 9);
                // icon_512
                $icon_512 = self::makeThumbnail($iconPath, $dir . '/icon_512' . '.png', 512, 512, 9);

                if ($icon_47 and $icon_57 and $icon_72 and $icon_114 and $icon_512) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * 
     * this function combines two png icons(512*512) and creates android icons from those two icons
     * @param your directory in which you want images to be created: $dir
     * @param your icon: $iconPath
     * @param your cover icon: $cover
     * @param name of image when icons have been combined $imageEndName
     * @return true or false
     * @example $myVar->combineTwoIcon("yourFolderForNewIcons","Myicon.png","Mycover.png","combineIcons.png")
     */
    public function combineTwoIcon($dir, $iconPath, $cover, $imageEndName) {
        if (!is_dir($dir)) {
            return false;
            exit();
        } else {
            $image_1 = imagecreatefrompng($iconPath);
            $image_2 = imagecreatefrompng($cover);
            imagealphablending($image_1, true);
            imagesavealpha($image_1, true);
            imagecopy($image_1, $image_2, 0, 0, 0, 0, 512, 512);
            imagepng($image_1, $dir . "/" . $imageEndName);
            $iconPath = $dir . "/" . $imageEndName;

            $type = getimagesize($iconPath);

            if ($type['mime'] == "image/png") {
                // icon_47
                $icon_47 = self::makeThumbnail($iconPath, $dir . '/icon_48' . '.png', 48, 48, 9);
                // icon_57
                $icon_57 = self::makeThumbnail($iconPath, $dir . '/icon_57' . '.png', 57, 57, 9);
                // icon_72
                $icon_72 = self::makeThumbnail($iconPath, $dir . '/icon_72' . '.png', 72, 72, 9);
                // icon_114
                $icon_114 = self::makeThumbnail($iconPath, $dir . '/icon_114' . '.png', 114, 114, 9);
                // icon_512
                $icon_512 = self::makeThumbnail($iconPath, $dir . '/icon_512' . '.png', 512, 512, 9);

                if ($icon_47 and $icon_57 and $icon_72 and $icon_114 and $icon_512) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    private function makeThumbnail($sourcefile, $endfile, $thumbwidth, $thumbheight, $quality) {
        // Load image and get image size.
        $img = imagecreatefrompng($sourcefile);
        $width = imagesx($img);
        $height = imagesy($img);

        if ($width == $height) {
            $newwidth = $thumbwidth;
            $newheight = $thumbheight;
        } else if ($width > $height) {
            $newwidth = $thumbwidth;
            $newheight = $thumbheight;
        } else {
            $newheight = $thumbheight;
            $newwidth = $thumbwidth;
        }

        // Create a new temporary image.
        $tmpimg = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($tmpimg, false);
        imagesavealpha($tmpimg, true);
        $transparent = imagecolorallocatealpha($tmpimg, 255, 255, 255, 127);
        imagefilledrectangle($tmpimg, 0, 0, $newwidth, $newheight, $transparent);
        // Copy and resize old image into new image.
        imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Save thumbnail into a file.
        $finalResult = imagepng($tmpimg, $endfile, $quality);

        // release the memory
        imagedestroy($tmpimg);
        imagedestroy($img);

        return $finalResult;
    }

    /**
     * returns a temporary filename in the system tmp dir
     * 
     * @param type $prefix for generated filename
     * @return string the name of the filename 
     */
    public function tmpfile($prefix = '') {
        $dir = sys_get_temp_dir() . "/";
        do {
            $path = $dir . $prefix . mt_rand(0, 9999999);
        } while (!touch($path));
        return $path;
    }

    /**
     * returns a temporary dir name in the system tmp dir
     * 
     * @param type $prefix prefix for the directory name
     * @param type $mode the mode for the file to be created
     * @return string the path for the created temporary directory
     */
    public function tempdir($prefix = '', $mode = 0700) {
        $dir = sys_get_temp_dir();
        if (substr($dir, -1) != '/') {
            $dir .= '/';
        }
        do {
            $path = $dir . $prefix . mt_rand(0, 9999999);
        } while (!mkdir($path, $mode));

        return $path;
    }

}

?>