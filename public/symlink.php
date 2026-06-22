<?php
// Script untuk membuat symbolic link di shared hosting tanpa SSH
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

// Set header plain text agar output terbaca rapi
header('Content-Type: text/plain');

if (file_exists($link) && !is_link($link)) {
    echo "Folder/file 'public/storage' sudah ada.\n";
    echo "Silakan hapus atau ganti nama folder tersebut terlebih dahulu melalui File Manager (cPanel) di hosting Anda sebelum menjalankan script ini.\n";
} else {
    if (is_link($link)) {
        echo "Symbolic link sudah terpasang.\n";
    } elseif (symlink($target, $link)) {
        echo "Sukses: Symbolic link berhasil dibuat!\n";
        echo "public/storage -> storage/app/public\n";
    } else {
        echo "Error: Gagal membuat symbolic link.\n";
        echo "Kemungkinan fungsi 'symlink()' dinonaktifkan oleh InfinityFree untuk alasan keamanan.\n";
        echo "Jika gagal, sangat direkomendasikan menggunakan metode alternatif dengan mengubah root path disk 'public' di 'config/filesystems.php'.\n";
    }
}
