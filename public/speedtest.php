<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'Speed Test';

$total_speed_tests = $db->getSpeedTests(0, 0, true);
$per_page = 50;
$total_pages = ceil($total_speed_tests / $per_page);
$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$previous_page = ($current_page > 1) ? $current_page - 1 : '';
$next_page = ($current_page < $total_pages) ? $current_page + 1 : '';
$offset = ($current_page - 1) * $per_page;
$speed_tests = $db->getSpeedTests($per_page, $offset);

include_once BASE_PATH . '/includes/header.php';
?>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"><h5 class="card-title text-uppercase"><?php echo $page_title ?></h5></div>
                        <div class="col-md-4 text-right"></div>
                    </div>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="chart-speed-test" width="400" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"><h5 class="card-title text-uppercase">Tabular Data</h5></div>
                        <div class="col-md-4 text-right"></div>
                    </div>
                    <?php if (!empty($speed_tests)): ?>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Download (Mbps)</th>
                                    <th scope="col">Upload (Mbps)</th>
                                    <th scope="col">Latency (ms)</th>
                                    <th scope="col">ISP</th>
                                    <th scope="col" class="text-left">Server Location</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($speed_tests as $speed_test): ?>
                                    <?php $speed_data = json_decode($speed_test->response_data); ?>
                                    <tr>
                                        <th scope="row"><?php echo $speed_test->speed_test_id ?></th>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($speed_test->date_created)) ?></td>
                                        <td><?php echo round($speed_test->download / 1024 / 1024, 2) ?></td>
                                        <td><?php echo round($speed_test->upload / 1024 / 1024, 2) ?></td>
                                        <td><?php echo round($speed_test->latency, 2) ?></td>
                                        <td><?php echo "{$speed_data->client->isp}<br>{$speed_data->client->ip}" ?></td>
                                        <td class="text-left"><?php echo "{$speed_data->server->name}<br>{$speed_data->server->sponsor} ({$speed_data->server->cc})" ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $previous_page == '' ? 'disabled' : '' ?>">
                                    <a class="page-link" href="./speedtest.php?page=<?php echo $previous_page; ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php $active = $i == $current_page ? 'active' : ''; ?>
                                    <li class="page-item <?php echo $active; ?>">
                                        <a class="page-link"
                                           href="./speedtest.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $next_page == '' ? 'disabled' : '' ?>">
                                    <a class="page-link" href="./speedtest.php?page=<?php echo $next_page; ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    <?php else: ?>
                        No data found.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php

function footer_content()
{
    global $speed_tests;
    $speed_tests = array_reverse($speed_tests);
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

        createChart('chart-speed-test');

        function createChart(elementId) {
            var ctx = document.getElementById(elementId);
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
                            borderWidth: 1,
                        },
                        {
                            label: 'Latency (ms)',
                            borderColor: window.chartColors.blue,
                            backgroundColor: window.chartColors.blue,
                            data: [<?php
                                $i = 0;
                                foreach ($speed_tests as $speed_test) {
                                    if ($i > 0) echo ",";
                                    echo round($speed_test->latency, 2);
                                    $i++;
                                }
                                ?>],
                            borderWidth: 1,
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
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }

    </script>
    <?php
}

include_once BASE_PATH . '/includes/footer.php';
