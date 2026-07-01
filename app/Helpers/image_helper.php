<?php

if (!function_exists('format_article_images')) {
    /**
     * Replaces remote/incorrect image src attributes in article content
     * with the local URL if the file exists in public/uploads/img_migrate
     */
    function format_article_images($isi) {
        // Clean up escaped quotes in the HTML first
        $isi = str_replace('\"', '"', $isi);
        $isi = str_replace("\'", "'", $isi);

        $dir = FCPATH . 'uploads/img_migrate';
        if (!is_dir($dir)) {
            return $isi;
        }
        
        static $localFiles = null;
        if ($localFiles === null) {
            // Get all files in the directory
            $files = scandir($dir);
            $localFiles = [];
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && is_file($dir . '/' . $file)) {
                    $localFiles[strtolower($file)] = $file;
                }
            }
        }
        
        // Regex to match clean img tags with src attribute
        return preg_replace_callback('/<img([^>]+)src="([^"]+)"/i', function($matches) use ($localFiles) {
            $imgTag = $matches[0];
            $srcVal = $matches[2];
            
            $filename = basename($srcVal);
            $lowerFilename = strtolower($filename);
            
            if (isset($localFiles[$lowerFilename])) {
                $localFilename = $localFiles[$lowerFilename];
                $newSrc = base_url('uploads/img_migrate/' . $localFilename);
                
                return str_replace($srcVal, $newSrc, $imgTag);
            }
            
            return $imgTag;
        }, $isi);
    }
}
