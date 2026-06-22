<?php

$source = __DIR__ . '/storage/app/public';
$destination = __DIR__ . '/public/storage';

function deleteStorageLinkOrFolder($path) {
    if (is_link($path)) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows symlink/junction deletion
            if (is_dir($path)) {
                rmdir($path);
            } else {
                unlink($path);
            }
        } else {
            unlink($path);
        }
        echo "Deleted existing symbolic link: $path\n";
    } elseif (is_dir($path)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($path);
        echo "Deleted existing directory: $path\n";
    } elseif (file_exists($path)) {
        unlink($path);
        echo "Deleted existing file: $path\n";
    }
}

function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recursiveCopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// 1. Clean up destination
deleteStorageLinkOrFolder($destination);

// 2. Perform copy
if (is_dir($source)) {
    recursiveCopy($source, $destination);
    echo "Successfully copied storage/app/public to public/storage!\n";
} else {
    echo "Source directory storage/app/public does not exist.\n";
}
