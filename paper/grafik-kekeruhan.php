<?php
include("../class.php");
$db = new database;
$koneksi = $db->koneksi();
date_default_timezone_set('Asia/Jakarta');

// Inisialisasi variabel data ph, turbiditas, dan temp
$phData = array();
$kekeruhanData = array();
$suhuData = array();
$timestamps = array();

$sql = "SELECT UNIX_TIMESTAMP(waktu) * 1000 AS milliseconds, ph, kekeruhan, suhu FROM fixed_sensor ORDER BY waktu;";
$result = $koneksi->query($sql);
$data = array();
// Ambil data dari hasil kueri
while ($row = $result->fetch_assoc()) {
    $timestamps = $row['milliseconds'];
    // $phData = $row['ph'];
    $kekeruhanData = $row['kekeruhan'];
    // $suhuData = $row['suhu'];

    $data[] = array($timestamps, $kekeruhanData);
}

// Konversi data waktu ke format yang diperlukan oleh grafik
// $timestamps = json_encode($timestamps);
// $phData = json_encode($phData);
// $kekeruhanData = json_encode($kekeruhanData);
// $suhuData = json_encode($suhuData);


$q = mysqli_query($koneksi, "SELECT waktu FROM fixed_sensor LIMIT 1;");
$d = mysqli_fetch_row($q);
$startTime = $d[0];

// info reset table id db
// ALTER TABLE sensor DROP id;
// ALTER TABLE sensor ADD id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
?>

<div id="reportsChart-kekeruhan-datetime"></div>

<script>
    var chart_kekeruhan;
    chart_kekeruhan = new ApexCharts(document.querySelector("#reportsChart-kekeruhan-datetime"), {
        series: [{
            name: 'Kekeruhan',
            data: [
                <?php foreach ($data as $item) {
                    $waktu = $item[0];
                    $kekeruhan = $item[1];
                    echo "[$waktu, $kekeruhan],";
                } ?>
            ]
        }],
        chart: {
            id: 'area-datetime',
            height: 250,
            type: 'area',
            zoom: {
                autoScaleYaxis: true
            },
            toolbar: {
                show: true,
                offsetX: 10,
                offsetY: -35,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true | '<img src="/static/icons/reload.png" width="20">'
                },
            },
            zoom: {
                enabled: true
            },
            pan: {
                enabled: true
            },
            animations: {
                enabled: false
            }
        },
        // annotations: {
        //     yaxis: [{
        //         y: 7,
        //         borderColor: '#999',
        //         label: {
        //             show: true,
        //             text: 'Netral',
        //             style: {
        //                 color: "#fff",
        //                 background: '#00E396'
        //             }
        //         }
        //     }],
        //     xaxis: [{

        //         x: new Date(anotasi),
        //         borderColor: '#999',
        //         yAxisIndex: 0,
        //         label: {
        //             show: true,
        //             text: 'Rally',
        //             style: {
        //                 color: "#fff",
        //                 background: '#775DD0'
        //             }
        //         }
        //     }]
        // },
        markers: {
            size: 0,
            style: 'hollow'
        },
        colors: ['#539165'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        legend: {
            position: 'top'
        },
        xaxis: {
            type: 'datetime',
            min: new Date('<?php echo $startTime ?>').getTime(),
            tickAmount: 6,
            tickPlacement: 'on',
            labels: {
                datetimeUTC: false
            },
            title: {
                text: 'W a k t u'
            }
        },
        yaxis: {
            title: {
                text: 'N T U'
            }
        },
        tooltip: {
            x: {
                format: 'dd/MMMM/yyyy HH:mm:ss'
            },
            y: {
                formatter: function(val) {
                    return val + ' NTU'
                }
            }
        }
    }).render();
</script>