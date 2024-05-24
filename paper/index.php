<?php
//cek session user dan pass kalo sudah habis atau hilang akan dibalikkan ke halaman login
session_start();
if (empty($_SESSION['user']) && empty($_SESSION['pass'])) {
  echo "<script>window.location.replace('../index.php')</script>";
}

include("../class.php");
$digi = new digilib;
$koneksi = $digi->koneksi();
$user = $_SESSION['user'];

// ambil keterangan profil
$profil = mysqli_query($koneksi, "SELECT nama,email,telepon,alamat,foto FROM login WHERE username='$user'");
$data_prof = mysqli_fetch_row($profil);
$namaAkun = $data_prof[0];
$emailAkun = $data_prof[1];
$telepon = $data_prof[2];
$alamat = $data_prof[3];
$fotoProfil = $data_prof[4];

// hapus akun
if (isset($_POST['tombol'])) {
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
  }
}

// ambil jumlah mahasiswa
$q1 = mysqli_query($koneksi, "SELECT COUNT(nim) FROM mhs");
$d1 = mysqli_fetch_row($q1);
$jumlahMhs = $d1[0];
// ambil jumlah dosen
$q2 = mysqli_query($koneksi, "SELECT COUNT(nip) FROM dosen");
$d2 = mysqli_fetch_row($q2);
$jumlahDosen = $d2[0];
// ambil jumlah akun
$q3 = mysqli_query($koneksi, "SELECT COUNT(username) FROM login");
$d3 = mysqli_fetch_row($q3);
$jumlahAkun = $d3[0];
// ambil jumlah pustaka
$q4 = mysqli_query($koneksi, "SELECT COUNT(id) FROM pustaka_1");
$d4 = mysqli_fetch_row($q4);
$jumlahPustaka = $d4[0];

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/">

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

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".alert ").fadeOut(5000);

      function loadDataGrafik() {
        $('#grafik-pH').load('grafik-pH.php');
        $('#grafik-suhu').load('grafik-suhu.php');
        $('#grafik-kekeruhan').load('grafik-kekeruhan.php');
        $('#grafik-do').load('grafik-kematian.php');
      };
      loadDataGrafik();

      $("#darkMode").click(function() {
        var teks1 = "Mode Malam";
        var teks2 = "Tapi Boong";

        var currentText = $('span.php').text();
        var newText = currentText === teks1 ? teks2 : teks1;

        $('span.php').text(newText);
        $('#darkMode .bx').toggleClass('bx-moon bx-sun');
      });
      $("#form_profil").submit(function(e) {
        // e.preventDefault(); // agar tidak merefresh halaman
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
    .toolbar {
      display: flex;
      justify-content: center;
      gap: 10px;
      padding-top: 10px;
      flex-wrap: wrap;
    }

    .toolbar button {
      outline: none;
    }

    .toolbar.ph {
      border-top: 1px solid #4154f1;
    }

    .toolbar.suhu {
      border-top: 1px solid #ff771d;
    }

    .toolbar.kekeruhan {
      border-top: 1px solid #539165;
    }

    .toolbar.do {
      border-top: 1px solid #873676;
    }

    .toolbar.ph button:hover,
    .toolbar.ph button:focus,
    .toolbar.ph button:active {
      color: #4154f1 !important;
      background-color: #fff !important;
    }

    .toolbar.suhu .btn-warning {
      background-color: #ff771d;
      border-color: #ff771d !important;
    }

    .toolbar.suhu button:hover,
    .toolbar.suhu button:focus,
    .toolbar.suhu button:active {
      color: #f18e0c !important;
      background-color: #fff !important;
    }

    .toolbar.kekeruhan .btn-success {
      background-color: #539165;
      border-color: #539165 !important;
    }

    .toolbar.kekeruhan button:hover,
    .toolbar.kekeruhan button:focus,
    .toolbar.kekeruhan button:active {
      color: #539165 !important;
      background-color: #fff !important;
    }

    .toolbar.do .btn-dark {
      background-color: #873676;
      border-color: #873676 !important;
    }

    .toolbar.do button:hover,
    .toolbar.do button:focus,
    .toolbar.do button:active {
      color: #873676 !important;
      background-color: #fff !important;
    }

    .card-title {
      text-align: left;
      margin-left: 25px;
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
          <a href="index.php" class="app-brand-link">
            <span class="app-brand-logo demo"><img src="../img/Smart Biofloc.png" alt="" style="margin: 30px 0 0 0;"></span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">DIGILIB</span> -->
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Forms & Tables -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Pilih Kolam</span></li>

          <!-- Dashboard -->
          <li class="menu-item active">
            <a href="index.php" class="menu-link">
              <i class='bx bx-radio-circle-marked me-2'></i>
              <div data-i18n="Analytics">Kolam 1</div>
            </a>
          </li>

          <!-- Tables -->
          <li class="menu-item">
            <a href="data-mahasiswa.php" class="menu-link">
              <i class='bx bx-radio-circle me-2'></i>
              <div data-i18n="Tables">Kolam 2</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="data-dosen.php" class="menu-link">
              <i class='bx bx-radio-circle me-2'></i>
              <div data-i18n="Tables">Kolam 3</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="data-login.php" class="menu-link">
              <i class='bx bx-radio-circle me-2'></i>
              <div data-i18n="Tables">Kolam 4</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="data-pustaka.php" class="menu-link">
              <i class='bx bx-radio-circle me-2'></i>
              <div data-i18n="Tables">Kolam 5</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase" style="margin: .1rem 0;"><span class="menu-header-text">Download</span></li>
          <li class="menu-item">
            <a href="data-pustaka.php" class="menu-link" style="color:crimson;">
              <i class='bx bxs-file-pdf me-2'></i>
              <div data-i18n="Tables">PDF</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="data-pustaka.php" class="menu-link" style="color:#539165;">
              <i class='bx bxs-file-txt me-2'></i>
              <div data-i18n="Tables">CSV / Data Tables</div>
            </a>
          </li>

        </ul>
        </li>
        </ul>
        <div class="app-brand demo">
          <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo"><img src="../img/polines-landscape.png" alt="" style="margin: 10px 0;"></span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">DIGILIB</span> -->
          </a>
        </div>
        <div class="mb-2 mb-md-0" style="
        position:relative;
        bottom: 10px; 
        left: 92px">
          <b>©
            <script>
              document.write(new Date().getFullYear());
            </script>
          </b>
        </div>
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

                    <!--/ User -->
                  </ul>
                <li style="padding-left: 20px;">
                  <a class="btn text-danger" href="../logout.php" style="box-shadow: 0 0 10px #ccc">
                    <i class="bx bx-log-out-circle me-2"></i>
                    <span class="align-middle">Keluar</span>
                  </a>
                </li>
              </ul>

            </div>
          </nav>
        </div>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-12 order-0 mb-3">
                <div class="card">
                  <div class="d-flex align-items-center row" style="text-align: center;">
                    <div class="col-sm-7">
                      <div class="card-body px-0">
                        <h5 class="card-title text-center mt-3">Selamat Pagi, <?php echo ucwords($namaAkun) ?> ! &#128075;&#127995;</h5>
                        <p class="mb-4">
                          Jelajahi website ini untuk melihat kondisi kolam kamu.
                        <div class="row">
                          <div class="col-2"></div>
                          <div class="col-8">
                            <li class="d-flex justify-content-center align-items-center">
                              <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-water"></i></span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-around gap-2">
                                <div class="me-2" style="padding-right:35px;border-right:2px solid #999;">
                                  <h6 class="mb-0">Ketinggian Air</h6>
                                  <small class="text-muted">01 Des 2023, 15.25 WIB</small>
                                </div>
                                <div class="user-progress">
                                  <small class="fw-semibold">1,33 meter</small><br>
                                  <i class='bx bxs-down-arrow-alt text-danger'></i>
                                  <small class="text-danger">2.02%</small>
                                </div>
                              </div>
                            </li>
                          </div>
                        </div>
                        <!-- Sekarang tanggal <span class="fw-bold">
                            <script>
                              document.write(new Date().getDate());
                            </script>
                            <script>
                              document.write(new Date().toLocaleString('default', {
                                month: 'long'
                              }));
                            </script>
                            <script>
                              document.write(new Date().getFullYear());
                            </script>.
                          </span> -->
                        </p>

                        <!-- <a href="#error" class="btn btn-sm btn-outline-primary"></a> -->
                      </div>
                    </div>
                    <div class="col-sm-5 text-center">
                      <div class="card-body pb-0 p-0 pe-5">
                        <li class="d-flex justify-content-center align-items-center mb-4">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class='bx bx-restaurant'></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-around gap-2">
                            <div class="me-2" style="padding-right:25px;border-right:2px solid #999;">
                              <h6 class="mb-0">Jumlah Pakan</h6>
                              <small class="text-muted">01 Des 2023, 08.00 WIB</small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">2633 gram</small><br>
                              <i class='bx bxs-down-arrow-alt text-danger'></i>
                              <small class="text-danger">13.71%</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex justify-content-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-time-five'></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-around gap-2">
                            <div class="me-2" style="padding-left:15px;padding-right:31px;border-right:2px solid #999;">
                              <h6 class="mb-0">Pemberian Pakan<br>Selanjutnya</h6>
                              <!-- <small class="text-muted">01 Des 2023, 17.45 WIB</small> -->
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">15.55 WIB</small><br>
                              <i class='bx bx-timer text-muted'></i>
                              <small class="text-muted">30 menit</small>
                            </div>
                          </div>
                        </li>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Order Statistics -->
              <div class="col-lg-12 order-3 order-md-2">
                <div class="row mb-2">
                  <div class="col-sm-6 container pe-1">
                    <div class="card">
                      <div class="card-body" style="text-align: center;">
                        <h5 class="card-title"><span style="color: #873676;">Cuaca</span></h5>
                        <div id="grafik-do"></div>
                        <div class="toolbar do">
                          <button class="btn btn-sm btn-dark" id="1menit">1 Minggu</button>
                          <button class="btn btn-sm btn-dark" id="1jam">1 Bulan</button>
                          <button class="btn btn-sm btn-dark" id="1hari">1 Tahun</button>
                          <!-- <button class="btn btn-sm btn-dark" id="1minggu">1 Minggu</button> -->
                          <!-- <button class="btn btn-sm btn-dark" id="1bulan">1 Bulan</button>
                          <button class="btn btn-sm btn-dark" id="1bulan">1 Tahun</button> -->
                          <button class="btn btn-sm btn-dark" id="reload"><i class='bx bx-refresh'></i> Refresh</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 container ps-1">
                    <div class="card">
                      <div class="card-body" style="text-align: center;">
                        <h5 class="card-title"><span style="color: #4154f1;">pH </span></h5>
                        <div id="grafik-pH"></div>
                        <div class="toolbar ph">
                          <!-- <button class="btn btn-sm btn-primary" id="1menit">1 Menit</button> -->
                          <button class="btn btn-sm btn-primary" id="1jam">1 Jam</button>
                          <button class="btn btn-sm btn-primary" id="1hari">1 Hari</button>
                          <button class="btn btn-sm btn-primary" id="1minggu">1 Minggu</button>
                          <!-- <button class="btn btn-sm btn-primary" id="1bulan">1 Bulan</button>
                          <button class="btn btn-sm btn-primary" id="1bulan">1 Tahun</button> -->
                          <button class="btn btn-sm btn-primary" id="reload"><i class='bx bx-refresh'></i> Refresh</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- </div>
    <div class="row"> -->

                </div>
                <div class="row mt-1">
                  <div class="col-sm-6 container pe-1">
                    <div class="card">
                      <div class="card-body" style="text-align: center;">
                        <h5 class="card-title"><span style="color: #ff771d;">Suhu </span></h5>
                        <div id="grafik-suhu"></div>
                        <div class="toolbar suhu">
                          <!-- <button class="btn btn-sm btn-warning" id="1menit">1 Menit</button> -->
                          <button class="btn btn-sm btn-warning" id="1jam">1 Jam</button>
                          <button class="btn btn-sm btn-warning" id="1hari">1 Hari</button>
                          <button class="btn btn-sm btn-warning" id="1minggu">1 Minggu</button>
                          <!-- <button class="btn btn-sm btn-primary" id="1bulan">1 Bulan</button>
                          <button class="btn btn-sm btn-primary" id="1bulan">1 Tahun</button> -->
                          <button class="btn btn-sm btn-warning" id="reload"><i class='bx bx-refresh'></i> Refresh</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 container ps-1">
                    <div class="card">
                      <div class="card-body" style="text-align: center;">
                        <h5 class="card-title"><span style="color: #539165;">Kekeruhan </span></h5>
                        <div id="grafik-kekeruhan"></div>
                        <div class="toolbar kekeruhan">
                          <!-- <button class="btn btn-sm btn-success" id="1menit">1 Menit</button> -->
                          <button class="btn btn-sm btn-success" id="1jam">1 Jam</button>
                          <button class="btn btn-sm btn-success" id="1hari">1 Hari</button>
                          <button class="btn btn-sm btn-success" id="1minggu">1 Minggu</button>
                          <!-- <button class="btn btn-sm btn-success" id="1bulan">1 Bulan</button>
                                            <button class="btn btn-sm btn-success" id="1bulan">1 Tahun</button> -->
                          <button class="btn btn-sm btn-success" id="reload"><i class='bx bx-refresh'></i> Refresh</button>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!--/ Transactions -->
              </div>

            </div>
            <!-- / Content -->

            <!-- Footer -->
            <!-- <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column" style="text-align: center !important;">

              <div>
                <a href="#" class="footer-link">Praktikum Web dan Basis Data</a>

                <a href="https://www.instagram.com/teb21.ofc/s" target="_blank" class="footer-link fw-bolder">| TE-2B</a>

              </div>
            </div>
        </div>
        </footer> -->
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
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>