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
    // $kekeruhanData = $row['kekeruhan'];
    $suhuData = $row['suhu'];

    $data[] = array($timestamps, $suhuData);
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

<div id="reportsChart-suhu-datetime"></div>

<script>
    // zingcharts
    // let myConfig = {
    //     type: 'area',
    //     legend: {
    //         header: {
    //             text: "Toolbar",
    //         }
    //     },
    //     crosshairX: {},
    //     legend: {
    //         draggable: true,
    //     },
    //     scaleX: {
    //         // Set scale label
    //         label: {
    //             text: 'Waktu'
    //         },
    //         item: {
    //             'font-size': 9
    //         },
    //         transform: {
    //             type: 'date',
    //             all: '%dd-%M-%Y %H:%i:%s'
    //         },
    //         // Convert text on scale indices
    //         zooming: true
    //     },
    //     scrollX: {},
    //     scrollY: {},
    //     scaleY: {
    //         // Scale label with unicode character
    //         label: {
    //             text: '( ° Celcius )'
    //         },
    //         zooming: true,
    //         values: '0:50:10',
    //         guide: {
    //             lineStyle: 'dashdot'
    //         },
    //     },
    //     plot: {
    //         aspect: 'spline',
    //         // Animation docs here:
    //         // https://www.zingchart.com/docs/tutorials/styling/animation#effect
    //         // animation: {
    //         //     effect: 'ANIMATION_EXPAND_BOTTOM',
    //         //     method: 'ANIMATION_STRONG_EASE_OUT',
    //         //     sequence: 'ANIMATION_BY_NODE',
    //         //     speed: 2000,
    //         // },
    //         rules: [{
    //                 rule: '%v < 20',
    //                 lineColor: 'red',
    //                 backgroundColor: 'red'
    //             },
    //             {
    //                 rule: '%v >= 20 && %v <= 40',
    //                 lineColor: 'orange',
    //                 backgroundColor: 'orange'
    //             },
    //             {
    //                 rule: '%v >= 40 && %v <= 50',
    //                 lineColor: 'orange',
    //                 backgroundColor: 'yellow'
    //             },
    //             {
    //                 rule: '%v >= 50 && %v <= 60',
    //                 lineColor: 'blue',
    //                 backgroundColor: 'green'
    //             },
    //             {
    //                 rule: '%v >= 60 && %v <= 80',
    //                 lineColor: 'blue',
    //                 backgroundColor: 'blue'
    //             },
    //             {
    //                 rule: '%v > 80',
    //                 lineColor: 'purple',
    //                 backgroundColor: 'purple'
    //             }
    //         ],
    //         marker: {
    //             size: 3,
    //             rules: [{
    //                     rule: '%v < 20',
    //                     backgroundColor: 'red'
    //                 },
    //                 {
    //                     rule: '%v >= 20 && %v <= 50',
    //                     backgroundColor: 'orange'
    //                 },
    //                 {
    //                     rule: '%v >= 50 && %v <= 80',
    //                     backgroundColor: 'blue'
    //                 },
    //                 {
    //                     rule: '%v > 80',
    //                     backgroundColor: 'purple'
    //                 }
    //             ]
    //         },
    //     },
    //     series: [{
    //         values: [
    //             ?php foreach ($data as $item) {
    //                 $waktu = $item[0];
    //                 $suhu = $item[1];
    //                 echo "[$waktu, $suhu],";
    //             } ?>
    //         ],
    //         text: "Suhu Kolam (°C)"
    //     }]
    // };
    // // enable native mobile plugin (NO MORE MOBILE PLUGIN REQUIRED)
    // zingchart.TOUCHZOOM = 'pinch';

    // zingchart.render({
    //     id: 'reportsChart-suhu-datetime',
    //     data: myConfig,
    //     height: 250,
    //     width: '100%'
    // });

    // // Refresh the chart every 5 seconds
    // setInterval(updateChart, 5000);

    // // apexcharts
    var chart_suhu;
    chart_suhu = new ApexCharts(document.querySelector("#reportsChart-suhu-datetime"), {
        series: [{
            name: 'Suhu',
            data: [
                <?php foreach ($data as $item) {
                    $waktu = $item[0];
                    $suhu = $item[1];
                    echo "[$waktu, $suhu],";
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
        colors: ['#ff771d'],
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
                text: '° C e l c i u s'
            }
        },
        tooltip: {
            x: {
                format: 'dd/MMMM/yyyy HH:mm:ss'
            },
            y: {
                formatter: function(val) {
                    return val + '°C'
                }
            }
        }
    }).render();
</script>