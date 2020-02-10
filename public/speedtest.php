<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'Speed Test';
$speed_tests = $db->getSpeedTests(30);

include_once BASE_PATH . '/includes/header.php';
?>
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="card" style="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"><h5 class="card-title text-uppercase"><?php echo $page_title ?></h5></div>
                        <div class="col-md-4 text-right">

                        </div>
                    </div>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
                    <canvas id="chart-speed-test" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
<?php

function footer_content()
{
    global $speed_tests;
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };
        var ctx = document.getElementById('chart-speed-test');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php
                    $i = 0;
                    foreach ($speed_tests as $speed_test) {
                        if ($i > 0) echo ",";
                        echo "'" . date('H:i d/m', strtotime($speed_test->date_created)) . "'";
                        $i++;
                    }
                    ?>],
                datasets: [
                    {
                        label: 'Upload (Mbps)',
                        borderColor: window.chartColors.orange,
                        backgroundColor: window.chartColors.orange,
                        data: [<?php
                            $i = 0;
                            foreach ($speed_tests as $speed_test) {
                                if ($i > 0) echo ",";
                                echo round($speed_test->upload / 1024 / 1024, 2);
                                $i++;
                            }
                            ?>],
                        borderWidth: 1
                    },
                    {
                        label: 'Download (Mbps)',
                        borderColor: window.chartColors.green,
                        backgroundColor: window.chartColors.green,
                        data: [<?php
                            $i = 0;
                            foreach ($speed_tests as $speed_test) {
                                if ($i > 0) echo ",";
                                echo round($speed_test->download / 1024 / 1024, 2);
                                $i++;
                            }
                            ?>],
                        borderWidth: 1
                    },
                    {
                        label: 'Latency (ms)',
                        borderColor: window.chartColors.blue,
                        backgroundColor: window.chartColors.blue,
                        data: [<?php
                            $i = 0;
                            foreach ($speed_tests as $speed_test) {
                                if ($i > 0) echo ",";
                                echo $speed_test->latency;
                                $i++;
                            }
                            ?>],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <?php
}

include_once BASE_PATH . '/includes/footer.php';
