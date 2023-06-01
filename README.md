note: konfigurasi database dapat diubah di file config.json

Aplikasi untuk view dan edit tabel relasi dalam postgreSQL. App ini dibuat karena
tugas yang diberikan oleh Bapak Guntur Dharma Putra, S.T., M.Sc.

App ini berinteraksi dengan database postgreSQL dengan konfigurasi ERD tabel terlampir di bawah:
![image](https://github.com/Adhisetama/TugasPostgreSQL_TekBasDat/assets/84426406/07d0f349-ac22-4468-a6f7-eecb3732d297)

Struktur directory:
```
+-- view
|    +-- index.php    // page untuk view tabel StoreBranches
|    +-- books.php    // page untuk view tabel Books
|    +-- staffs.php   // page untuk view tabel Staffs
|    +-- authors.php  // page untuk view tabel Authors
|
+-- modules
|    +-- dbConnection.php // file untuk membuat koneksi ke database
|                         // juga berisi function untuk query
|-- edit
|    +-- authors.php  // page untuk mengupdate tabel Authors pada DB
|    +-- query.php    // page untuk melakukan query ke DB dari query string
|                     // yang dihasilkan dari form update
|                     // (method post dikirimkan ke page ini)
+-- assets
     +-- icon.png     // icon
```
