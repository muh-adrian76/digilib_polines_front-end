<?php
session_start();
if (empty($_SESSION['user']) && empty($_SESSION['pass'])) {
    echo "<script>window.location.replace('../index.php')</script>";
}
//koneksi
include("../class.php");
$digi = new digilib;
$koneksi = $digi->koneksi();
$user = $_SESSION['user'];

$profil = mysqli_query($koneksi, "SELECT nama,email,telepon,alamat,foto FROM login WHERE username='$user'");
$data_prof = mysqli_fetch_row($profil);
$namaAkun = $data_prof[0];
$emailAkun = $data_prof[1];
$telepon = $data_prof[2];
$alamat = $data_prof[3];
$fotoProfil = $data_prof[4];

if (isset($_POST['tombol'])) {
    // if ($_POST["tombol"] == "simpan") {
    //     $judul = $_POST['judul'];
    //     $tipe = $_POST['tipe'];
    //     $tahun = $_POST['tahun'];
    //     $pembimbing1 = $_POST['pembimbing1'];
    //     $pembimbing2 = $_POST['pembimbing2'];
    //     $ketua_penguji = $_POST['ketua_penguji'];
    //     $sekretaris = $_POST['sekretaris'];
    //     $penguji1 = $_POST['penguji1'];
    //     $penguji2 = $_POST['penguji2'];
    //     $penguji3 = $_POST['penguji3'];
    //     // query create
    //     mysqli_query($koneksi, "INSERT INTO pustaka_1(judul,tipe,tahun,pembimbing_1,pembimbing_2,ketua_penguji,penguji1,penguji2,penguji3,sekretaris) VALUES('$judul','$tipe','$tahun','$pembimbing1','$pembimbing2','$ketua_penguji','$penguji1','$penguji2','$penguji3','$sekretaris')");
    //     if (mysqli_affected_rows($koneksi) > 0)
    //         echo '<div class="alert alert-success" style="
    //       max-width: 50%;
    //       z-index: 999999999999;
    //       margin: auto;
    //       position: absolute;
    //       top: 10px;
    //       left: 50%;
    //       text-align:center;
    //       transform: translateX(-50%);
    //       "><strong>Berhasil</strong> menambahkan Laporan!</div>';
    //     else
    //         echo '<div class="alert alert-danger" style="
    //       max-width: 50%;
    //       z-index: 999999999999;
    //       margin: auto;
    //       position: absolute;
    //       top: 10px;
    //       left: 50%;
    //       text-align:center;
    //       transform: translateX(-50%);
    //       "><strong>Gagal</strong> menambahkan Laporan!</div>';
    //     $tombol_val = "simpan";
    //     $cardHeader = "Tambah";
    //     $id = "";
    //     $judul = "";
    //     $tipe = "";
    //     $tahun = "";
    //     $pembimbing1 = "";
    //     $pembimbing2 = "";
    //     $ketua_penguji = "";
    //     $sekretaris = "";
    //     $penguji1 = "";
    //     $penguji2 = "";
    //     $penguji3 = "";
    if ($_POST['tombol'] == 'simpanAkun') {
        $namaAkun = $_POST['namaAkun'];
        $emailAkun = $_POST['emailAkun'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];

        //upload file
        $target_dir = "assets/img/avatars/";
        $file_foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $file_foto;
        //query update pustaka1
        if (!empty($file_foto)) {
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
            mysqli_query($koneksi, "UPDATE login SET nama='$namaAkun', email='$emailAkun', telepon='$telepon', alamat='$alamat', foto='$file_foto' WHERE username='$user'");
            echo '<div class="alert alert-success" style="
              max-width: 50%;
              z-index: 999999999999;
              margin: auto;
              position: absolute;
              top: 10px;
              left: 50%;
              text-align:center;
              transform: translateX(-50%);
              "><strong>Berhasil</strong> mengubah Profil!</div>';
            header("Refresh:0");
        } else {
            mysqli_query($koneksi, "UPDATE login SET nama='$namaAkun', email='$emailAkun', telepon='$telepon', alamat='$alamat' WHERE username='$user'");
            echo '<div class="alert alert-success" style="
              max-width: 50%;
              z-index: 999999999999;
              margin: auto;
              position: absolute;
              top: 10px;
              left: 50%;
              text-align:center;
              transform: translateX(-50%);
              "><strong>Berhasil</strong> mengubah Profil!</div>';
        }
    } elseif ($_POST['tombol'] == 'hapusAkun') {
        mysqli_query($koneksi, "DELETE FROM login WHERE username='$user'");
        session_unset();
        session_destroy();
        echo "<script>window.location.replace('../index.php')</script>";
    } elseif ($_POST["tombol"] == "edit") {
        $id_judul = $_POST['id_judul'];
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

        //upload file
        $target_dir = "../laporan/";
        $nama_file = basename($_FILES["laporan"]["name"]);
        $target_file = $target_dir . $nama_file;

        //jika nama file diisi
        if (!empty($nama_file)) move_uploaded_file($_FILES["laporan"]["tmp_name"], $target_file);
        //query update pustaka1
        if (!empty($nama_file)) {
            mysqli_query($koneksi, "UPDATE pustaka_1 SET judul='$judul', tipe='$tipe', tahun='$tahun',pembimbing_1='$pembimbing1',pembimbing_2='$pembimbing2',ketua_penguji='$ketua_penguji',sekretaris='$sekretaris',penguji1='$penguji1',penguji2='$penguji2',penguji3='$penguji3',nama_file='$nama_file' WHERE id='$id_judul'");
        } else {
            mysqli_query($koneksi, "UPDATE pustaka_1 SET judul='$judul', tipe='$tipe', tahun='$tahun',pembimbing_1='$pembimbing1',pembimbing_2='$pembimbing2',ketua_penguji='$ketua_penguji',sekretaris='$sekretaris',penguji1='$penguji1',penguji2='$penguji2',penguji3='$penguji3' WHERE id='$id_judul'");
        }

        //update pustaka2
        mysqli_query($koneksi, "DELETE FROM pustaka_2 WHERE id_judul='$id_judul'");

        for ($i = 0; $i < count($mhs); $i++) {
            $e = explode(' ', $mhs[$i]);
            mysqli_query($koneksi, "INSERT INTO pustaka_2 (id_judul,nim) VALUES ('$id_judul','$e[0]')");
        }

        if (mysqli_affected_rows($koneksi) > 0) {
            echo '<div class="alert alert-success" style="
          max-width: 50%;
          z-index: 999999999999;
          margin: auto;
          position: absolute;
          top: 10px;
          text-align:center;
          left: 50%;
           transform: translateX(-50%);
          "><strong>Berhasil</strong> mengubah data Laporan!</div>';
            $tombol_val = "simpan";
            $cardHeader = "Tambah";
            $nama_form = "form_pustaka";
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
        } else {
            echo '<div class="alert alert-danger" style="
          max-width: 50%;
          z-index: 999999999999;
          margin: auto;
          position: absolute;
          top: 10px;
          left: 50%;
          text-align:center;
          transform: translateX(-50%);
          "><strong>Gagal</strong> mengubah data Laporan!</div>';
            $tombol_val = "edit";
            $cardHeader = "Edit";
        }
    } elseif ($_POST["tombol"] == 'hapus') {
        $id_judul = $_POST['id_judul'];

        $q = mysqli_query($koneksi, "SELECT nama_file FROM pustaka_1 WHERE id='$id_judul'");
        $d = mysqli_fetch_row($q);

        //hapus file
        if (!empty($d[0])) {
            $laporan = "../laporan/$d[0]";
            unlink($laporan);
        }
        // mysqli_query($koneksi, "ALTER TABLE pustaka_1 DROP id");
        // mysqli_query($koneksi, "ALTER TABLE pustaka_1 ADD id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");

        //hapus data dari pustaka_1
        mysqli_query($koneksi, "DELETE FROM pustaka_1 WHERE id='$id_judul'");
        //hapus data dari pustaka_2
        mysqli_query($koneksi, "DELETE FROM pustaka_2 WHERE id_judul='$id_judul'");

        $judul = "";
        $nama_file = "";
        $nama_form = "form_pustaka";

        mysqli_query($koneksi, "ALTER TABLE pustaka_2 DROP id");
        mysqli_query($koneksi, "ALTER TABLE pustaka_2 ADD id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        echo '<div class="alert alert-success" style="
          max-width: 50%;
          z-index: 999999999999;
          margin: auto;
          position: absolute;
          top: 10px;
          left: 50%;
          text-align:center;
          transform: translateX(-50%);
          "><strong>Berhasil</strong> menghapus Laporan!</div>';
        // $id = "";
        // $judul = "";
        // $tipe = "";
        // $tahun = "";
        // $pembimbing1 = "";
        // $pembimbing2 = "";
        // $ketua_penguji = "";
        // $penguji1 = "";
        // $penguji2 = "";
        // $penguji3 = "";
        // $sekretaris = "";
        // $cardHeader = "Tambah";
        // $tombol_val = "simpan";
    }
}

// ELSEIF DEFAULT
elseif (isset($_GET['t'])) {
    if ($_GET['t'] == 'edit') {
        $id = $_GET['id'];
        //ambil data laporan
        $q = mysqli_query($koneksi, "SELECT judul,tipe,tahun,pembimbing_1,pembimbing_2,ketua_penguji,sekretaris,penguji1,penguji2,penguji3,nama_file  FROM pustaka_1 WHERE id='$id'");
        $d = mysqli_fetch_row($q);
        $judul = $d[0];
        $tipe = $d[1];
        $tahun = $d[2];
        $pembimbing1 = $d[3];
        $pembimbing2 = $d[4];
        $ketua_penguji = $d[5];
        $sekretaris = $d[6];
        $penguji1 = $d[7];
        $penguji2 = $d[8];
        $penguji3 = $d[9];
        $nama_file = $d[10];

        $nama_form = "form_pustaka_edit";
        $tombol_val = "edit";
    }
} else {
    $id = "";
    $judul = "";
    $tipe = "";
    $tahun = "";
    $pembimbing1 = "";
    $pembimbing2 = "";
    $ketua_penguji = "";
    $penguji1 = "";
    $penguji2 = "";
    $penguji3 = "";
    $sekretaris = "";
    $cardHeader = "Tambah";
    $tombol_val = "simpan";
    $nama_form = "form_pustaka";
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin Dashboard</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../img/polines.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../css/style.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../assets/vendor/simple-datatables/datatables.min.css">

    <!-- Page CSS -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <!-- edit data -->
    <script>
        $(document).ready(function() {

            $(".alert").fadeOut(5000);
            // mode malam
            $("#darkMode").click(function() {
                var teks1 = "Mode Malam";
                var teks2 = "Tapi Boong";

                var currentText = $('span.php').text();
                var newText = currentText === teks1 ? teks2 : teks1;

                $('span.php').text(newText);
                $('#darkMode .bx').toggleClass('bx-moon bx-sun');
            });
            table = $('#datatable').DataTable({
                "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                "language": {
                    "lengthMenu": 'Tampilkan <select>' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="100">100</option>' +
                        '</select> baris data'
                },
                columnDefs: [{
                        targets: [1, 9],
                        orderable: false
                    } // Disable ordering for the first column (index 0)
                ]
            });
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            // modal delete
            $("a:nth-child(2)").click(function() {
                console.log("diklik lho..."); // untuk mengetes selektor
                id_judul = $(this).attr('href'); // untuk variable judul dari attribut href
                judul = $(this).attr('data-judul');
                $(".modal-title.delete").text("Konfirmasi Hapus");
                $(".modal-body.delete").text("Apakah anda yakin ingin menghapus laporan nomor " + judul + " ?");

                form1 = "<form method=post><input type=hidden name=id_judul value=" + id_judul + ">";
                form2 = "<button type=simpan name=tombol value=hapus class=\"btn btn-danger m-2\" data-bs-dismiss=modal>Ya</button>";
                form3 = "<button type=button class=\"btn btn-warning\" data-bs-dismiss=modal>Tidak</button>";
                form4 = "</form>";
                form = form1 + form2 + form3 + form4;
                $(".modal-footer.delete").empty();
                $(".modal-footer.delete").append(form);
            });
            // select tipa laporan
            $("select[name='tipe']").on("change", function() {
                tipe = $(this).find(":selected").val();
                if (tipe == "M") {
                    $("#ketua,#penguji,label[for='pembimbing2'],select[name='pembimbing2'],.button_plus").fadeOut(1000);
                } else {
                    $("#ketua,#penguji,label[for='pembimbing2'],select[name='pembimbing2'],.button_plus").fadeIn(1000);
                }
            });
            // pembimbing 1 = ketua penguji
            $("select[name='pembimbing1']").on("change", function() {
                pembimbing1 = $(this).find(":selected").val();
                $("select[name='ketua_penguji']").val(pembimbing1);
            });
            $("select[name='ketua_penguji']").on("change", function() {
                ketua_penguji = $(this).find(":selected").val();
                $("select[name='pembimbing1']").val(ketua_penguji);
            });
            $(".button_plus").click(function() {
                mhs1 = "<div class='row g-2 mb-3 d-flex justify-content-center' id='mhs_del'><div class='col-md hstack'>";
                mhs2 = "<input type=text class='form-control' name=mhs[] value='' placeholder='Masukkan Nama Mahasiswa' />";
                mhs3 = "<button class='btn btn-outline-primary ms-2 button_min2' type=button><i class='bx bxs-user-minus'></i></button>";
                mhs4 = "</div></div>";
                mhs_add = mhs1 + mhs2 + mhs3 + mhs4;

                $("button[name='mhs_add']").after(mhs_add);
                // autocomplete
                $("input[name='mhs[]']").keyup(function() {
                    mhs_nama = $(this).val(); // mengambil data inputan yang kita ketik
                    console.log("nama : " + mhs_nama);
                    $.ajax({
                            method: "POST",
                            url: "../data-ajax.php",
                            data: {
                                p: "mhs",
                                nama: mhs_nama
                            },
                            dataType: "json"
                        })
                        .done(function(data) { //kalau sukses maka data akan ditampilkan
                            //panjang = data.length
                            $("input[name='mhs[]']").autocomplete({
                                source: data
                            });
                        })
                        .fail(function(msg) {
                            console.log("error : " + msg)
                        })
                });
                $(".button_min").click(function() {
                    $("#mhs_del").remove();
                });
            });
            // tombol tambah mahasiswa
            $(".button_plus2").click(function() {
                mhs1 = "<div class='row g-2 d-flex justify-content-center mb-3' id='mhs_del2'><div class='col-md-12 hstack'>";
                mhs2 = "<input type=text class='form-control' name=mhs[] value='' placeholder='Masukkan Nama Mahasiswa' />";
                mhs3 = "<button class='btn btn-outline-primary ms-2 button_min2' type=button><i class='bx bxs-user-minus'></i></button>";
                mhs4 = "</div></div>";
                mhs_add2 = mhs1 + mhs2 + mhs3 + mhs4;

                $("div.spasi").before(mhs_add2);
                // autocomplete
                $("input[name='mhs[]']").keyup(function() {
                    mhs_nama = $(this).val(); // mengambil data inputan yang kita ketik
                    console.log("nama : " + mhs_nama);
                    $.ajax({
                            method: "POST",
                            url: "../data-ajax.php",
                            data: {
                                p: "mhs",
                                nama: mhs_nama
                            },
                            dataType: "json"
                        })
                        .done(function(data) { //kalau sukses maka data akan ditampilkan
                            //panjang = data.length
                            $("input[name='mhs[]']").autocomplete({
                                source: data
                            });
                        })
                        .fail(function(msg) {
                            console.log("error : " + msg)
                        })
                });
                $(".button_min2").click(function() {
                    $("#mhs_del2").remove();
                });
            });
            // ajax autocomplete mhs[]
            $("input[name='mhs[]']").keyup(function() {
                mhs_nama = $(this).val(); // mengambil data inputan yang kita ketik
                console.log("nama : " + mhs_nama);
                $.ajax({
                        method: "POST",
                        url: "../data-ajax.php",
                        data: {
                            p: "mhs",
                            nama: mhs_nama
                        },
                        dataType: "json"
                    })
                    .done(function(data) { //kalau sukses maka data akan ditampilkan
                        //panjang = data.length
                        $("input[name='mhs[]']").autocomplete({
                            source: data
                        });
                    })
                    .fail(function(msg) {
                        console.log("error : " + msg)
                    })
            });
            // form pustaka
            $("#form_pustaka").submit(function(e) {
                e.preventDefault(); // agar tidak merefresh halaman
                var formData = new FormData(this);
                $.ajax({
                        url: "../data-ajax.php",
                        type: "POST",
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false
                    })
                    .done(function() {
                        console.log("oke");
                    })
                    .fail(function(msg) {
                        console.log("error : " + msg);
                    })
            });
        });
    </script>
    <style>
        .dataTables_filter,
        .dataTables_info {
            display: none;
        }

        .dataTables_paginate {
            padding: 10px;
        }

        .dataTables_length {
            margin-top: 20px;
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="#" class="app-brand-link">
                        <span class="app-brand-logo demo"><img src="../img/logo-text.png" alt="" style="margin: 30px 0;"></span>
                        <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">DIGILIB</span> -->
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="index.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <!-- Forms & Tables -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Form &amp; Tabel</span></li>

                    <!-- Tables -->
                    <li class="menu-item">
                        <a href="data-mahasiswa.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                            <div data-i18n="Tables">Data Mahasiswa</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="data-dosen.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                            <div data-i18n="Tables">Data Dosen</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="data-login.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-lock-open-alt"></i>
                            <div data-i18n="Tables">Data Login</div>
                        </a>
                    </li>
                    <li class="menu-item active">
                        <a href="data-pustaka.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-file-archive"></i>
                            <div data-i18n="Tables">Data Pustaka</div>
                        </a>
                    </li>
                    <!-- shortcut web -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Tautan</span></li>
                    <li class="menu-item">
                        <a href="https://main.polines.ac.id/id/" target="blank" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-globe"></i>
                            <div data-i18n="Analytics">POLINES</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" target="blank" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-globe"></i>
                            <div data-i18n="Analytics">DIGILIB</div>
                        </a>
                    </li>
                </ul>
                </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Modal Akun dan Notif -->
            <!-- Modal 1-->
            <div class="modal fade" id="akun" aria-labelledby="akun" tabindex="-1" data-bs-backdrop="static" style="display: none" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-center" style="text-align: center;">
                            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-target="#akun" data-bs-toggle="modal"><i class="bx bx-user me-1"></i> Akun</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-target="#notifikasi" data-bs-toggle="modal"><i class="bx bx-bell me-1"></i> Notifikasi</button>
                                </li>
                            </ul>
                        </div>
                        <div class="modal-body">
                            <div class="card mb-4">
                                <h5 class="card-header" style="text-align: center;">Profile Details</h5>
                                <!-- Account -->
                                <div class="card-body">
                                    <form id="form_profil" enctype="multipart/form-data" method="POST">
                                        <input type="hidden" class="form-control" name="p" value="profil" required />
                                        <div class="d-flex justify-content-center align-items-start align-items-sm-center gap-4">
                                            <img src="../assets/img/avatars/<?php echo $fotoProfil ?>" alt="user-avatar" class="d-block rounded" height="120" width="120" id="uploadedAvatar" />
                                            <div class="button-wrapper">
                                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                    <span class="d-none d-sm-block" style="text-align: start !important;">Upload Foto</span>
                                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                                    <input type="file" id="upload" name="foto" class="account-file-input" value="" hidden accept="image/png, image/jpeg" />
                                                </label>

                                                <p class="text-muted mb-0">Format JPG atau PNG.<br>Ukuran Maksimum 5 Mb</p>
                                            </div>
                                        </div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body" style="text-align: center;">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="namaAkun" class="form-label">Nama</label>
                                            <input class="form-control" type="text" id="namaAkun" name="namaAkun" value="<?php echo $namaAkun ?>" placeholder="Masukkan Nama Anda" autofocus />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="emailAkun" class="form-label">E-mail</label>
                                            <input class="form-control" type="email" id="emailAkun" name="emailAkun" value="<?php echo $emailAkun ?>" placeholder="contoh@email.com" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="telepon">Nomor Telepon</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text">Wakanda (+62)</span>
                                                <input type="text" id="telepon" name="telepon" value="<?php echo $telepon ?>" class="form-control" placeholder="89 012 345 678" />
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>" placeholder="Alamat Anda" />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" name="tombol" value="simpanAkun" data-bs-dismiss="modal" class="btn btn-primary me-2">Simpan</button>
                                    </div>
                                    </form>
                                </div>
                                <!-- /Account -->
                            </div>
                            <div class="card" style="text-align: center;">
                                <h5 class="card-header">Hapus Akun</h5>
                                <div class="card-body">
                                    <div class="mb-3 col-12 mb-0">
                                        <div class="alert-warning" style="position: relative;
                                                      padding: 0.9375rem 0.9375rem;
                                                      margin-bottom: 1rem;
                                                      border: 0 solid transparent;
                                                      border-radius: 0.375rem;
                                                      text-align: center !important;">
                                            <h6 class="alert-heading fw-bold mb-1">Apakah anda yakin ingin menghapus akun ini ?</h6>
                                            <p class="mb-0">Apakah anda sudah ikhlas ?</p>
                                        </div>
                                    </div>
                                    <form id="formAccountDeactivation" method="POST">
                                        <div class="form-check mb-3 d-flex justify-content-center">
                                            <input class="form-check-input me-2" type="checkbox" name="accountActivation" id="accountActivation" required />
                                            <label class="form-check-label" for="accountActivation">Saya sudah ikhlas lillahita'ala</label>
                                        </div>
                                        <button type="submit" name="tombol" value="hapusAkun" class="btn btn-danger deactivate-account">Nonaktifkan Akun</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal 2-->
            <div class="modal fade" id="notifikasi" aria-hidden="true" aria-labelledby="notifikasi" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-center" style="text-align: center;">
                            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-target="#akun" data-bs-toggle="modal"><i class="bx bx-user me-1"></i> Akun</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-target="#notifikasi" data-bs-toggle="modal"><i class="bx bx-bell me-1"></i> Notifikasi</button>
                                </li>
                            </ul>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <!-- Notifications -->
                                <h5 class="card-header">Recent Devices</h5>
                                <div class="card-body">
                                    <span>We need permission from your browser to show notifications.
                                        <span class="notificationRequest"><strong>Request Permission</strong></span></span>
                                    <div class="error"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap">Type</th>
                                                <th class="text-nowrap text-center">✉️ Email</th>
                                                <th class="text-nowrap text-center">🖥 Browser</th>
                                                <th class="text-nowrap text-center">👩🏻‍💻 App</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-nowrap">New for you</td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck1" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck2" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck3" checked />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap">Account activity</td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck4" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck5" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck6" checked />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap">A new browser used to sign in</td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck7" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck8" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck9" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap">A new device is linked</td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck10" checked />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck11" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" id="defaultCheck12" />
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <h6>When should we send you notifications?</h6>
                                    <form action="javascript:void(0);">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select id="sendNotification" class="form-select" name="sendNotification">
                                                    <option selected>Only when I'm online</option>
                                                    <option>Anytime</option>
                                                </select>
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                                <button type="reset" class="btn btn-outline-secondary">Discard</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /Notifications -->
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <div class="sticky">
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <!-- Search -->
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item d-flex align-items-center" id="datatable_filter">
                                    <i class="bx bx-search fs-4 lh-0"></i>
                                    <input type="text" id="search" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." aria-controls="datatable" />
                                </div>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- Place this tag where you want the button to render. -->
                                <!-- <li id="darkMode" class="nav-item me-3 btn btn-sm btn-outline-dark">
                  <i class="bx bx-moon me-2"></i><span class="php">Mode Malam</span>
                </li> -->

                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="../assets/img/avatars/<?php echo $fotoProfil ?>" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#akun">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <img src="../assets/img/avatars/<?php echo $fotoProfil ?>" alt class="w-px-40 h-auto rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-semibold d-block"><?php echo ucwords($namaAkun) ?></span>
                                                        <small class="text-muted">Admin</small>
                                                    </div>
                                                </div>
                                            </button>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bx bx-message-square-detail me-2"></i>
                                                <span class="align-middle">Pesan</span>
                                                <span class="badge badge-center rounded-pill bg-primary align-middle mx-2 w-px-20 h-px-20">4</span>
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#notifikasi">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Pengaturan</span>
                                            </button>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="../logout.php">
                                                <i class="bx bx-log-out-circle me-2"></i>
                                                <span class="align-middle">Keluar</span>
                                            </a>
                                        </li>
                                        <!--/ User -->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Modal Akun dan Notif -->
                    <!-- Modal 1-->
                    <div class="modal fade" id="akun" aria-labelledby="akun" tabindex="-1" style="display: none" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalToggleLabel">Modal 1</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">Show a second modal and hide this one with the button below.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-bs-target="#modalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">
                                        Open second modal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal 2-->
                    <div class="modal fade" id="notifikasi" aria-hidden="true" aria-labelledby="notifikasi" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalToggleLabel2">Modal 2</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">Hide this modal and show the first with the button below.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-bs-target="#akun" data-bs-toggle="modal" data-bs-dismiss="modal">
                                        Back to first
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal DELETE -->
                    <div class="modal fade" id="hapusPustaka" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-center">
                                    <h5 class="modal-title delete" id="modalCenterTitle">Form Mahasiswa Baru</h5>
                                </div>
                                <div class="modal-body delete">
                                    <form action="data-mahasiswa.php" method="POST">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Mahasiswa Baru" required />
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col mb-0">
                                                <label for="nip" class="form-label">NIP</label>
                                                <input type="text" id="nip" name="nip" class="form-control" <?php echo $status_nip ?> placeholder="Masukkan NIM Mahasiswa Baru" required />
                                            </div>
                                            <div class="col mb-0">
                                                <label for="kelas" class="form-label">Kelas</label>
                                                <select class="form-select" name="kelas" id="kelas" aria-label="Default select example" required>
                                                    <option selected>Pilih Kelas</option>
                                                    <?php
                                                    $k = array('TK-1A', 'TK-1B', 'TE-1A', 'TE-1B', 'TK-2A', 'TK-2B', 'TE-2A', 'TE-2B', 'TK-3A', 'TK-3B', 'TE-3A', 'TE-3B', 'TK-4A', 'TK-4B', 'TE-4A', 'TE-4B');
                                                    foreach ($k as $kls) {
                                                        if ($kls == $kelas) $sel = "SELECTED";
                                                        else $sel = "";
                                                        echo "<option value='$kls'> $kls </option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer delete">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Tutup
                                    </button>
                                    <button type="simpan" name="tombol" value="<?php echo $tombol_val ?>" class="btn btn-primary">Simpan</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y" style="text-align: center !important;">
                        <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light">Form /</span> Data Pustaka</h4>

                        <!-- Basic Layout & Basic with Icons -->
                        <div class="row">
                            <!-- Basic Layout -->
                            <div class="col-xxl">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-center">
                                        <h5 class="mb-0"><?php echo $cardHeader ?> Pustaka</h5>

                                    </div>
                                    <div class="card-body">
                                        <form action="data-pustaka.php" id="<?php echo $nama_form ?>" method="POST" enctype="multipart/form-data">
                                            <?php
                                            if (isset($_GET['t']))
                                                echo "<input type=hidden name=id_judul value='$_GET[id]'>";
                                            else
                                                echo "<input type=hidden class='form-control' name=p value=pustaka required />";
                                            ?>
                                            <label class="col-sm-2 col-form-label" for="judul">Judul</label>
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <input type="text" id="judul" name="judul" value="<?php echo $judul ?>" class="form-control" placeholder="Masukkan Judul Laporan" required />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="col-form-label" for="tipe">Tipe</label>
                                                    <select class="form-select" name="tipe" id="tipe" aria-label="Default select example" required>
                                                        <option selected>Pilih Tipe Laporan</option>
                                                        <?php
                                                        $t = array('M', 'T');
                                                        foreach ($t as $tp) {
                                                            if ($tp == $tipe) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$tp' $sel> $tp </option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="col-form-label" for="tahun">Tahun</label>
                                                    <select class="form-select" name="tahun" id="tahun" aria-label="Default select example" required>
                                                        <option selected>Pilih Tahun</option>
                                                        <?php
                                                        $th = array('2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');
                                                        foreach ($th as $thn) {
                                                            if ($thn == $tahun) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$thn' $sel> $thn </option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="tipe">Pembimbing 1</label>
                                                    <select class="form-select" name="pembimbing1" id="pembimbing1" aria-label="Default select example" required>
                                                        <option selected>Pilih Dosen Pembimbing</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $pembimbing1) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="pembimbing2">Pembimbing 2</label>
                                                    <select class="form-select" name="pembimbing2" id="pembimbing2" aria-label="Default select example" required>
                                                        <option selected>Pilih Dosen Pembimbing</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $pembimbing2) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3" id="ketua">
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="ketua_penguji">Ketua Penguji</label>
                                                    <select class="form-select" name="ketua_penguji" id="ketua_penguji" aria-label="Default select example" required>
                                                        <option selected>Pilih Ketua Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $ketua_penguji) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-form-label" for="sekretaris">Sekretaris</label>
                                                    <select class="form-select" name="sekretaris" id="sekretaris" aria-label="Default select example" required>
                                                        <option selected>Pilih Sekretaris</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $sekretaris) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3" id="penguji">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="col-form-label" for="penguji1">Penguji 1</label>
                                                    <select class="form-select" name="penguji1" id="penguji1" aria-label="Default select example" required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $penguji1) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="col-form-label" for="penguji2">Penguji 2</label>
                                                    <select class="form-select" name="penguji2" id="penguji2" aria-label="Default select example" required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $penguji2) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="file">File Laporan</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="laporan" id="file" />
                                                        <label class="input-group-text" for="file">Upload</label>
                                                    </div>
                                                    <p><?php echo "<a href=\"../laporan/$nama_file\" target=_blank>$nama_file</a>"; ?>
                                                    </p>
                                                </div>
                                                <div class="col-lg-6 mb-3" id="penguji">
                                                    <label class="col-form-label" for="penguji3">Penguji 3</label>
                                                    <select class="form-select" name="penguji3" id="penguji3" aria-label="Default select example" required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            if ($d[0] == $penguji3) $sel = "SELECTED";
                                                            else $sel = "";
                                                            echo "<option value='$d[0]' $sel> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 d-flex justify-content-center hstack mb-3">

                                                <label class="col-form-label" for="mahasiswa">Mahasiswa</label>
                                                <?php
                                                $nm = 0;
                                                if (isset($_GET['t'])) {
                                                    $id_judul = $_GET['id'];
                                                    $q = mysqli_query($koneksi, "SELECT nim FROM pustaka_2 WHERE id_judul='$id_judul'");
                                                    while ($d = mysqli_fetch_row($q)) {
                                                        $nm++;
                                                        $nim = $d[0];
                                                        $nama_mhs = $digi->nim_to_nama($nim);

                                                        if ($nm == 1) {
                                                            $id_mhs = "mhs";
                                                            $button_class = "button_plus2";
                                                            $button_ket = "bxs-user-plus";
                                                        } else {
                                                            $id_mhs = "mhs_del2";
                                                            $button_class = "button_min2";
                                                            $button_ket = "bxs-user-minus";
                                                        }
                                                ?>
                                                        <div class="row mb-3 d-flex justify-content-center" id="<?php echo $id_mhs ?>">
                                                            <div class="col-md hstack">
                                                                <input type="text" class="form-control me-2" name="mhs[]" value="<?php echo "$nim $nama_mhs"; ?>" placeholder="Masukkan Nama Mahasiswa" required />
                                                                <button type="button" name="mhs_add2" class="btn btn-outline-primary <?php echo $button_class ?>"><i class="bx <?php echo $button_ket ?>"></i></button>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="row g-2 d-flex justify-content-center" id="mhs">
                                                        <div class="col-md hstack">
                                                            <input type="text" class="form-control me-2" value="" placeholder="Masukkan Nama Mahasiswa" name="mhs[]">
                                                            <button type="button" name="mhs_add2" class="btn btn-outline-primary button_plus2"><i class="bx bxs-user-plus"></i></button>

                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>

                                            </div>
                                            <div class="row mt-3 mb-3 d-flex spasi justify-content-center">
                                                <div class="col-md-6">
                                                    <button type="submit" name="tombol" value="<?php echo $tombol_val ?>" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <hr class="my-3" />
                        <h4 class="fw-bold py-3 mb-4" style="text-align: center !important;"><span class="text-muted fw-light">Tabel Data /</span> Pustaka</h4>

                        <!-- Hoverable Table rows -->
                        <div class="card overflow-hidden" style="max-height:620px; padding:10px 20px;">
                            <div class="table-responsive">
                                <table class="table table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="dt-head-center" scope="col">Judul</th>
                                            <th class="dt-head-center" scope="col">File Laporan</th>
                                            <th class="dt-head-center" scope="col">Tipe</th>
                                            <th class="dt-head-center" scope="col">Tahun</th>
                                            <th class="dt-head-center" scope="col">Mahasiswa</th>
                                            <th class="dt-head-center" scope="col">Pembimbing</th>
                                            <th class="dt-head-center" scope="col">Ketua Penguji</th>
                                            <th class="dt-head-center" scope="col">Sekretaris</th>
                                            <th class="dt-head-center" scope="col">Penguji</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        $q = mysqli_query($koneksi, "SELECT * FROM pustaka_1 ORDER BY tipe ASC, tahun ASC");
                                        while ($d = mysqli_fetch_row($q)) {
                                            $id = $d[0];
                                            $judul = $d[1];
                                            $tipe = $d[2];
                                            $tahun = $d[3];
                                            $pembimbing1 = $digi->nip_to_nama($d[4]);
                                            $pembimbing2 = $digi->nip_to_nama($d[5]);
                                            $ketua_penguji = $digi->nip_to_nama($d[6]);
                                            $sekretaris = $digi->nip_to_nama($d[7]);
                                            $penguji1 = $digi->nip_to_nama($d[8]);
                                            $penguji2 = $digi->nip_to_nama($d[9]);
                                            $penguji3 = $digi->nip_to_nama($d[10]);
                                            $nama_file = $d[11];

                                            echo "
                          <tr> 
                            <td>$judul</td>
                            <td> <button type=button class='btn btn-outline-primary hover'><a href=\"../laporan/$nama_file\" class='hoverFile' target='_blank'><i class='bx bxs-file-find'></i></a></button></td>
                            <td><span class=\"badge bg-label-primary\">$tipe</span></td>
                            <td><span class=\"badge bg-label-dark\">$tahun</span></td>
                            <td><strong>";

                                            // data mahasiswa
                                            $n = 0;
                                            $q2 = mysqli_query($koneksi, "SELECT nim FROM pustaka_2 WHERE id_judul='$id'");
                                            while ($d2 = mysqli_fetch_row($q2)) {
                                                $n++;
                                                echo "$n. " . $digi->nim_to_nama($d2[0]) . "<br>";
                                            }

                                            echo "
                            </strong></td>
                            <td> <strong>1. $pembimbing1<br>2. $pembimbing2</strong></td>
                            <td> <strong>$ketua_penguji</strong></td>
                            <td> <strong>$sekretaris</strong></td>
                            <td> <strong>1. $penguji1<br>2. $penguji2<br>3. $penguji3</strong></td>
                            <td>
                                <div class=\"dropdown\">
                                    <button type=button class=\"btn p-0 dropdown-toggle hide-arrow\" data-bs-toggle=dropdown>
                                    <i class=\"bx bx-dots-vertical-rounded\"></i>
                                    </button>
                                    <div class=\"dropdown-menu\">
                                    <a class=\"text-info dropdown-item\" 
                                        href=\"$_SERVER[PHP_SELF]?t=edit&id=$id\">
                                        <i class=\"bx bx-edit-alt me-1\"></i> Edit
                                    </a>
                                    <a class=\"text-secondary dropdown-item\" 
                                        href='$id' data-judul='$judul'
                                        data-bs-toggle=modal 
                                        data-bs-target=#hapusPustaka>
                                        <i class=\"bx bx-trash me-1\"></i> Delete
                                    </a>
                                    </div>
                                </div>
                            </td> 
                          </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column" style="text-align: center !important;">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>

                                <a href="#" class="footer-link fw-bolder">| 4.31.21.1.18 | 4.31.21.1.22</a>
                            </div>
                            <div>
                                <a href="#" class="footer-link">Praktikum Web dan Basis Data</a>

                                <a href="https://www.instagram.com/teb21.ofc/s" target="_blank" class="footer-link fw-bolder">| TE-2B</a>

                            </div>
                        </div>
                </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>