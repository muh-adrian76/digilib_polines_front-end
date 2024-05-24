<?php
include "class.php";
$digi = new digilib;
$koneksi = $digi->koneksi();

if (isset($_POST['p'])) {
    $p = $_POST['p'];
    if ($p == "mhs") {
        $nama = $_POST['nama'];
        $q = mysqli_query($koneksi, "SELECT nim,nama,kelas FROM mhs WHERE nama LIKE '%$nama%'");
        while ($d = mysqli_fetch_row($q)) {
            $label = '[' . $d[2] . '] ' . $d[0] . ' ' . $d[1];
            $value = $d[0] . ' ' . $d[1];
            $data[] = array('label' => $label, 'value' => $value);
        }
        echo json_encode($data);
    } elseif ($p == "pustaka") {
        $judul = $_POST['judul'];
        $tipe = $_POST['tipe'];
        $tahun = $_POST['tahun'];
        $pembimbing1 = $_POST['pembimbing1'];
        $pembimbing2 = $_POST['pembimbing2'];
        $ketua_penguji = $_POST['ketua_penguji'];
        $sekretaris = $_POST['sekretaris'];
        $penguji1 = $_POST['penguji1'];
        $penguji2 = $_POST['penguji2'];
        $penguji3 = $_POST['penguji3'];
        $mhs = $_POST['mhs'];

        if ($tipe == "M") $ketua_penguji = "";

        //upload file
        $target_dir = "laporan/";
        $nama_file = basename($_FILES["laporan"]["name"]);
        $target_file = $target_dir . $nama_file;
        move_uploaded_file($_FILES["laporan"]["tmp_name"], $target_file);

        mysqli_query($koneksi, "INSERT INTO pustaka_1 (judul,tipe,tahun,pembimbing_1,pembimbing_2,ketua_penguji,penguji1,penguji2,penguji3,sekretaris,nama_file) VALUES ('$judul','$tipe','$tahun','$pembimbing1','$pembimbing2','$ketua_penguji','$penguji1','$penguji2','$penguji3','$sekretaris','$nama_file')");
        $last_id = mysqli_insert_id($koneksi);
        for ($i = 0; $i < count($mhs); $i++) {
            $e = explode(' ', $mhs[$i]);
            mysqli_query($koneksi, "INSERT INTO pustaka_2(id_judul,nim) VALUES ('$last_id','$e[0]')");
        }
        $judul = "";
        $tipe = "";
        $tahun = "";
        $pembimbing1 = "";
        $pembimbing2 = "";
        $ketua_penguji = "";
        $sekretaris = "";
        $penguji1 = "";
        $penguji2 = "";
        $penguji3 = "";
        $mhs = "";
        $nama_file = "";
    } elseif ($p == "profil") {
        $namaAkun = $_POST['namaAkun'];
        $emailAkun = $_POST['emailAkun'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];


        //upload file
        $target_dir = "assets/img/avatars/";
        $file_foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $file_foto;

        if (!empty($file_foto)) move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        //query update pustaka1
        if (!empty($file_foto)) {
            mysqli_query($koneksi, "UPDATE login SET nama='$namaAkun', email='$emailAkun', telepon='$telepon', alamat='$alamat', foto='$file_foto' WHERE username='$user'");
        } else {
            mysqli_query($koneksi, "UPDATE login SET nama='$namaAkun', email='$emailAkun', telepon='$telepon', alamat='$alamat' WHERE username='$user'");
        }
    }
}
