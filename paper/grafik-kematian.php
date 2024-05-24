<?php
include("../class.php");
$db = new database;
$koneksi = $db->koneksi();
date_default_timezone_set('Asia/Jakarta');

$q3 = mysqli_query($koneksi, "SELECT waktu FROM kematian LIMIT 1;");
$d3 = mysqli_fetch_row($q3);
$startDeath = $d3[0];

// data kematian
$sql_death = "SELECT UNIX_TIMESTAMP(waktu) * 1000 AS milliseconds, jumlah FROM kematian ORDER BY waktu";
$resultDeath = $koneksi->query($sql_death);
$dataKematian = array();
while ($rowDeath = $resultDeath->fetch_assoc()) {
    $timeDeath = $rowDeath['milliseconds'];
    $deathData = $rowDeath['jumlah'];

    $dataKematian[] = array($timeDeath, $deathData);
};
?>
<div id="reportsChart-kematian"></div>

<script>
    var chart_kematian;
    chart_kematian = new ApexCharts(document.querySelector("#reportsChart-kematian"), {
        series: [{
            name: 'Jumlah',
            data: [
                <?php foreach ($dataKematian as $itemKematian) {
                    $waktuKematian = $itemKematian[0];
                    $kematian = $itemKematian[1];
                    echo "[$waktuKematian, $kematian],";
                } ?>
            ]
        }],
        chart: {
            id: 'area-datetime',
            type: 'area',
            height: 250,
            zoom: {
                autoScaleYaxis: true
            },
            toolbar: {
                show: true,
                offsetX: 10,
                offsetY: -35
            },
            zoom: {
                enabled: true
            },
            pan: {
                enabled: true
            },
            animations: {
                enabled: true
            }
        },
        // colors: ['#EB5353'],
        colors: ['#873676'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            min: new Date('<?php echo $startDeath ?>').getTime(),
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
                // text: 'E k o r'
                text: 'K o n d i s i'
            },
            labels: {
                align: 'right',
                minWidth: 0,
                maxWidth: 50,
                style: {
                    colors: ["#776e81"],
                    fontSize: '10px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
                formatter: function(value) {
                    if (value === 1) {
                        return "Hujan";
                    } else if (value === 0) {
                        return "Cerah";
                    } else {
                        return "";
                    }
                }
            }
        },
        // fill: {
        //     type: "gradient",
        //     gradient: {
        //         shadeIntensity: 1,
        //         opacityFrom: 0.3,
        //         opacityTo: 0.7,
        //         stops: [0, 100]
        //     }
        // },
        tooltip: {
            x: {
                format: 'dd/MMMM/yyyy'
            },
            y: {
                formatter: function(val) {
                    // return val + " ekor"
                    return val + " mg / L"
                }
            }
        }
    }).render();
</script>