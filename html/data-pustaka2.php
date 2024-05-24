<?php
session_start();
if (empty($_SESSION['user']) && empty($_SESSION['pass'])) {
    echo "<script>window.location.replace('../index.php')</script>";
}
//koneksi
include("../class.php");
$digi = new digilib;
$koneksi = $digi->koneksi();

if (isset($_POST['tombol'])) {
    if ($_POST["tombol"] == "simpan") {
        $id = $_POST['id'];
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
        // query create
        mysqli_query($koneksi, "INSERT INTO pustaka_1 VALUES('$id','$judul','$tipe','$tahun','$pembimbing1','$pembimbing2','$ketua_penguji','$penguji1','$penguji2','$penguji3','$sekretaris')");
        if (mysqli_affected_rows($koneksi) > 0)
            echo '<div class="alert alert-success" style="
          max-width: 50%;
          z-index: 999999999999;
          margin: auto;
          position: absolute;
          top: 10px;
          left: 50%;
          text-align:center;
          transform: translateX(-50%);
          "><strong>Berhasil</strong> menambahkan Laporan!</div>';
        else
            echo '<div class="alert alert-danger" style="
          max-width: 50%;
          z-index: 999999999999;
          margin: auto;
          position: absolute;
          top: 10px;
          left: 50%;
          text-align:center;
          transform: translateX(-50%);
          "><strong>Gagal</strong> menambahkan Laporan!</div>';
        $tombol_val = "simpan";
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
        $no_edit = "readonly";
        $no_edit_option = "disabled";
    } elseif ($_POST["tombol"] == "edit") {
        $id = $_POST['id'];
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
        //query update
        mysqli_query($koneksi, "UPDATE pustaka_1 SET judul='$judul', tipe='$tipe', pembimbing_1='$pembimbing1', pembimbing_2='$pembimbing2' WHERE id='$id'");
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
            $no_edit = "readonly";
            $no_edit_option = "disabled";
            $disableModal = "";
        } else
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
        $disableModal = "disabled";
    } elseif ($_POST["tombol"] == 'hapus') {
        $id = $_POST['id'];
        mysqli_query($koneksi, "DELETE FROM pustaka_1 WHERE id='$id'");
        mysqli_query($koneksi, "ALTER TABLE pustaka_1 DROP id");
        mysqli_query($koneksi, "ALTER TABLE pustaka_1 ADD id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
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
        $tombol_val = "simpan";
        $no_edit = "readonly";
        $no_edit_option = "disabled";
    }
}

// ELSEIF DEFAULT
elseif (isset($_GET['t'])) {
    if ($_GET['t'] == 'edit') {
        $id = $_GET['id'];
        //ambil data laporan
        $q = mysqli_query($koneksi, "SELECT judul,tipe,pembimbing_1,pembimbing_2 FROM pustaka_1 WHERE id='$id'");
        $d = mysqli_fetch_row($q);
        $judul = $d[0];
        $tipe = $d[1];
        $pembimbing1 = $d[2];
        $pembimbing2 = $d[3];

        $tombol_val = "edit";
        $disableModal = "disabled";
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
    $tombol_val = "simpan";
    $no_edit = "readonly";
    $no_edit_option = "disabled";
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
    <link rel="icon" type="image/x-icon" href="../img/logo-notext.png" />

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
            // modal delete
            $("a:nth-child(2)").click(function() {
                console.log("diklik lho..."); // untuk mengetes selektor
                id = $(this).attr('href'); // untuk variable judul dari attribut href
                $(".modal-title.delete").text("Konfirmasi Hapus");
                $(".modal-body.delete").text("Apakah anda yakin ingin menghapus laporan nomor " + id + " ?");

                form1 = "<form method=post><input type=hidden name=id value=" + id + ">";
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
            $("#form_pustaka").submit(function(e) {
                e.preventDefault(); // agar tidak merefresh halaman
                var formData = new FormData(this);
                $.ajax({
                        url: "../ajax.php",
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
            // tombol tambah mahasiswa
            $(".button_plus").click(function() {
                mhs1 = "<div class='row g-2 mb-3 d-flex justify-content-center' id='mhs_del'><div class='col-md-6 mb-3 hstack'>";
                mhs2 = "<input type=text class='form-control' value='' placeholder='Masukkan Nama Mahasiswa' name=mhs[]>";
                mhs3 = "<button class='btn btn-outline-primary ms-2 button_min' type=button><i class='bx bxs-user-minus'></i></button>";
                mhs4 = "</div></div>";
                mhs_add = mhs1 + mhs2 + mhs3 + mhs4;

                $("button[name='mhs_add']").before(mhs_add);
                $(".button_min").click(function() {
                    $("#mhs_del").remove();
                });
            });
            $(".button_plus2").click(function() {
                mhs1 = "<div class='row g-2 mb-3' id='mhs_del2'><div class='col-md-6 mb-3 hstack'></div><div class='col-md-6 mb-3 hstack'>";
                mhs2 = "<input type=text class='form-control' value='' placeholder='Masukkan Nama Mahasiswa'  <?php echo $no_edit ?> name=mhs[]>";
                mhs3 = "<button class='btn btn-outline-primary ms-2 button_min2' type=button  <?php echo $no_edit_option ?>><i class='bx bxs-user-minus'></i></button>";
                mhs4 = "</div></div>";
                mhs_add = mhs1 + mhs2 + mhs3 + mhs4;

                $("input[name='spasi']").after(mhs_add);
                $(".button_min2").click(function() {
                    $("#mhs_del2").remove();
                });
            });
        });
    </script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <span class="app-brand-logo demo"><img src="../img/logo-text.png" alt=""></span>
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
                        <a href="https://main.polines.ac.id/id/" target="blank" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-globe"></i>
                            <div data-i18n="Analytics">Halaman Web Polines</div>
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
                    <li class="menu-item">
                        <a href="data-pustaka.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-file-archive"></i>
                            <div data-i18n="Tables">Data Pustaka</div>
                        </a>
                    </li>
                </ul>
                </li>
                </ul>
            </aside>
            <!-- / Menu -->

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
                                <div class="nav-item d-flex align-items-center">
                                    <i class="bx bx-search fs-4 lh-0"></i>
                                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                                </div>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- Place this tag where you want the button to render. -->
                                <!-- <li id="darkMode" class="nav-item me-3 btn btn-sm btn-outline-dark">
                                <i class="bx bx-moon me-2"></i><span class="php">Mode Malam</span>
                            </li> -->

                                <!-- User -->

                                <li>
                                    <a href="../logout.php" class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-log-out-circle me-2"></i>Keluar
                                    </a>
                                </li>

                                <!--/ User -->
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Modal POST -->
                    <div class="modal fade" id="tambahPustaka" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl ui-front" role="document">
                            <div class="modal-content" style="text-align: center !important;">
                                <div class="modal-header d-flex justify-content-center">
                                    <h5 class="modal-title" id="modalCenterTitle">Form Laporan Baru</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="data-pustaka.php" id="form_pustaka" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" class="form-control" name="p" value="pustaka" required />
                                        <input type="hidden" class="form-control" id="id" name="id" required />
                                        <label class="col-sm-2 col-form-label" for="judul">Judul</label>
                                        <div class="row mb-3">
                                            <div class="col-sm-12">
                                                <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan Judul Laporan" required />
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
                                                        if ($tp == $tipe) $sel = "";
                                                        else $sel = "";
                                                        echo "<option value='$tp' $sel> $tp </option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="col-form-label" for="tahun">Tahun</label>
                                                <select class="form-select" name="tahun" id="tahun" aria-label="Default select example" required>
                                                    <option selected>Pilih Tahun</option>
                                                    <?php
                                                    $th = array('2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');
                                                    foreach ($th as $thn) {
                                                        if ($thn == $tahun) $sel = "";
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
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-form-label" for="pembimbing2">Pembimbing 2</label>
                                                <select class="form-select" name="pembimbing2" id="pembimbing2" aria-label="Default select example" required>
                                                    <option selected>Pilih Dosen Pembimbing</option>
                                                    <?php
                                                    $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                    while ($d = mysqli_fetch_row($q)) {
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
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
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
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
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3" id="penguji">
                                            <div class="col-md-6 mb-3">
                                                <label class="col-form-label" for="penguji1">Penguji 1</label>
                                                <select class="form-select" name="penguji1" id="penguji1" aria-label="Default select example" required>
                                                    <option selected>Pilih Dosen Penguji</option>
                                                    <?php
                                                    $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                    while ($d = mysqli_fetch_row($q)) {
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="col-form-label" for="penguji2">Penguji 2</label>
                                                <select class="form-select" name="penguji2" id="penguji2" aria-label="Default select example" required>
                                                    <option selected>Pilih Dosen Penguji</option>
                                                    <?php
                                                    $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                    while ($d = mysqli_fetch_row($q)) {
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6 mb-3" id="penguji">
                                                <label class="col-form-label" for="penguji3">Penguji 3</label>
                                                <select class="form-select" name="penguji3" id="penguji3" aria-label="Default select example" required>
                                                    <option selected>Pilih Dosen Penguji</option>
                                                    <?php
                                                    $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                    while ($d = mysqli_fetch_row($q)) {
                                                        echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-form-label" for="file">File Laporan</label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control" name="laporan" id="file" />
                                                    <label class="input-group-text" for="file">Upload</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3 d-flex justify-content-center">
                                            <div class="col-md-6 mb-3">
                                                <label class="col-form-label" for="mahasiswa">Mahasiswa</label>
                                                <input type="text" class="form-control" name="mhs[]" id="mahasiswa" placeholder="Masukkan Nama Mahasiswa" />
                                            </div>
                                        </div>
                                        <div class="row" style="text-align: center !important;">
                                            <div class="col mb-3"><button type="button" name="mhs_add" class="btn btn-outline-primary button_plus"><i class="bx bxs-user-plus"></i> Tambah Mahasiswa</button></div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                            <button type="simpan" name="tombol" value="<?php echo $tombol_val ?>" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form /</span> Data Pustaka</h4>

                        <!-- Basic Layout & Basic with Icons -->
                        <div class="row">
                            <!-- Basic Layout -->
                            <div class="col-xxl">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-center">
                                        <h5 class="mb-0">Edit Pustaka</h5>

                                    </div>
                                    <div class="card-body">
                                        <form action="" id="form_pustaka" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" class="form-control" name="p" value="pustaka" required />
                                            <input type="hidden" class="form-control" value="<?php echo $id ?>" id="id" name="id" required />
                                            <label class="col-sm-2 col-form-label" for="judul">Judul</label>
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <input type="text" id="judul" name="judul" value="<?php echo $judul ?>" class="form-control" <?php echo $no_edit ?> placeholder="Masukkan Judul Laporan" required />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="col-form-label" for="tipe">Tipe</label>
                                                    <select class="form-select" name="tipe" id="tipe" aria-label="Default select example" <?php echo $no_edit_option ?> required>
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
                                                    <select class="form-select" name="tahun" id="tahun" aria-label="Default select example" <?php echo $no_edit_option ?> required>
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
                                                    <select class="form-select" name="pembimbing1" id="pembimbing1" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Dosen Pembimbing</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="pembimbing2">Pembimbing 2</label>
                                                    <select class="form-select" name="pembimbing2" id="pembimbing2" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Dosen Pembimbing</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3" id="ketua">
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="ketua_penguji">Ketua Penguji</label>
                                                    <select class="form-select" name="ketua_penguji" id="ketua_penguji" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Ketua Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-form-label" for="sekretaris">Sekretaris</label>
                                                    <select class="form-select" name="sekretaris" id="sekretaris" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Sekretaris</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3" id="penguji">
                                                <div class="col-lg-4 mb-3">
                                                    <label class="col-form-label" for="penguji1">Penguji 1</label>
                                                    <select class="form-select" name="penguji1" id="penguji1" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="col-form-label" for="penguji2">Penguji 2</label>
                                                    <select class="form-select" name="penguji2" id="penguji2" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="col-form-label" for="penguji3">Penguji 3</label>
                                                    <select class="form-select" name="penguji3" id="penguji3" aria-label="Default select example" <?php echo $no_edit_option ?> required>
                                                        <option selected>Pilih Dosen Penguji</option>
                                                        <?php
                                                        $q = mysqli_query($koneksi, "SELECT nip,gelar_depan,nama,gelar_belakang FROM dosen ORDER BY nama ASC");
                                                        while ($d = mysqli_fetch_row($q)) {
                                                            echo "<option value='$d[0]'> $d[1] $d[2], $d[3]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 d-flex justify-content-center mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="file">File Laporan</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="laporan" id="file" <?php echo $no_edit_option ?> required />
                                                        <label class="input-group-text" for="file">Upload</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="col-form-label" for="mahasiswa">Mahasiswa</label>
                                                    <input type="text" class="form-control" name="mhs[]" id="mahasiswa" <?php echo $no_edit ?> placeholder="Masukkan Nama Mahasiswa" required />
                                                </div>
                                            </div>
                                            <input type="hidden" name="spasi">
                                            <div class="row mb-3 d-flex justify-content-center">
                                                <div class="col-md-6">
                                                    <button type="button" name="mhs_add2" class="btn btn-outline-primary button_plus2" <?php echo $no_edit_option ?>><i class="bx bxs-user-plus"></i> Tambah Mahasiswa</button>
                                                    <button type="submit" name="tombol" value="<?php echo $tombol_val ?>" <?php echo $no_edit_option ?> class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button trigger modal -->
                    <div class="center">
                        <button type="button" class="btn btn-primary" <?php echo $disableModal ?> data-bs-toggle="modal" data-bs-target="#tambahPustaka">
                            Tambah Pustaka Baru
                        </button>
                    </div>


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <hr class="my-4" />
                        <h4 class="fw-bold py-3 mb-4" style="text-align: center !important;"><span class="text-muted fw-light">Tabel Data /</span> Pustaka</h4>

                        <!-- Hoverable Table rows -->
                        <div class="card overflow-hidden" style="height:500px;">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th scope="col">Judul</th>
                                            <th scope="col">File Laporan</th>
                                            <th scope="col">Tipe</th>
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Mahasiswa</th>
                                            <th scope="col">Pembimbing</th>
                                            <th scope="col">Ketua Penguji</th>
                                            <th scope="col">Sekretaris</th>
                                            <th scope="col">Penguji</th>
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
                            <td>
                                <div class=\"dropdown\">
                                    <button type=button class=\"btn p-0 dropdown-toggle hide-arrow\" data-bs-toggle=dropdown>
                                    <i class=\"bx bx-dots-vertical-rounded\"></i>
                                    </button>
                                    <div class=\"dropdown-menu\">
                                    <a class=\"text-info dropdown-item\" 
                                        href=\"data-pustaka.php?t=edit&id=$id\">
                                        <i class=\"bx bx-edit-alt me-1\"></i> Edit
                                    </a>
                                    <a class=\"text-secondary dropdown-item\" 
                                        href='$id'
                                        data-bs-toggle=modal 
                                        data-bs-target=#hapusPustaka>
                                        <i class=\"bx bx-trash me-1\"></i> Delete
                                    </a>
                                    </div>
                                </div>
                                </td>  
                            <td>$judul</td>
                            <td> <button type=button class='btn btn-outline-primary'><a href='../laporan/$nama_file\' target='_blank'></a><i class='bx bxs-file-find'></i></button></td>
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