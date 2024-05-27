<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    $current_page = 'admin_dashboard';


    $totalItem = 0;
    $totalCategory;

    try {
        $getRequestStmt = $conn->prepare('SELECT CAST(request_date AS Date) AS date, count(*) AS totalDay
                                            FROM request
                                            WHERE request_date >= DATE(NOW() - INTERVAL 180 DAY)
                                            GROUP BY date
                                            HAVING COUNT(totalDay)
                                            ORDER BY request_date DESC;');
        $getRequestStmt->execute();
        $getRequestResult = $getRequestStmt->get_result();
        $getRequestArray = array();
        if (mysqli_num_rows($getRequestResult) > 0) {
            while ($getRequest = $getRequestResult->fetch_assoc()) {
                $getRequestArray[$getRequest['date']] = $getRequest['totalDay'];
            }
        }

        $getRequestStatusStmt = $conn->prepare('SELECT request_status, count(*) as totalStatus
                                                    FROM request
                                                    GROUP BY request_status
                                                    HAVING count(totalStatus);');
        $getRequestStatusStmt->execute();
        $getRequestStatusResult = $getRequestStatusStmt->get_result();
        $getRequestStatusArray = array();
        if (mysqli_num_rows($getRequestStatusResult) > 0) {
            while ($getRequestStatus = $getRequestStatusResult->fetch_assoc()) {
                $getRequestStatusArray[$getRequestStatus['request_status']] = $getRequestStatus['totalStatus'];
            }
        }
        
    } catch (Throwable $e) {
        echo $e;
        echo mysqli_error($conn);
    }

    try {
        $getProductStmt = $conn->prepare('SELECT pc.cate_name, pro.product_name
                                            FROM productcategory pc
                                            INNER JOIN product pro
                                            ON pc.product_cate_id = pro.product_cate_id
                                            WHERE pro.product_status = 1
                                            ORDER BY pc.product_cate_id ASC;');
        $getProductStmt->execute();
        $getProductResult = $getProductStmt->get_result();
        
        if(mysqli_num_rows($getProductResult) > 0) {
            $getProductArray = array();
            $tempArray = array();
            $previousCode;
            
            while ($getProduct = $getProductResult->fetch_assoc()) {
                if (!isset($previousCode)) {
                    $previousCode = $getProduct['cate_name'];
                    array_push($tempArray, $getProduct['product_name']);
                } else {
                    if ($previousCode == $getProduct['cate_name']) {
                        array_push($tempArray, $getProduct['product_name']);
                    } else {
                        $getProductArray[$previousCode] = $tempArray;
                        $tempArray = array();
                        array_push($tempArray, $getProduct['product_name']);
                    }

                }

                $previousCode = $getProduct['cate_name'];
            }

            $getProductArray[$previousCode] = $tempArray;
        }
       

        if (isset($getProductArray)) {
            foreach ($getProductArray as $productCate => $product) {
                $totalItem = count($product) + $totalItem;
            }
            $totalCategory = count($getProductArray);
        }





        
    } catch (Throwable $e) {
        echo $e;
        echo mysqli_error($conn);
    }

    try {
        $getProductRequestedStmt = $conn->prepare('SELECT pro.product_name, SUM(rd.request_quan) as totalProduct
                                                    FROM requestdetail rd
                                                    INNER JOIN product pro
                                                    ON rd.product_id = pro.product_id
                                                    INNER JOIN request rq
                                                    ON rq.request_id = rd.request_id
                                                    WHERE rq.request_status = 1
                                                    GROUP BY rd.product_id
                                                    ORDER BY totalProduct DESC
                                                    LIMIT 10;');
                                                        
        $getProductRequestedStmt->execute();
        $getProductRequestedResult = $getProductRequestedStmt->get_result();
        $getProductRequestedArray = array();
        $totalProductRequested = 0;
        if (mysqli_num_rows($getProductRequestedResult) > 0) {
          
            while ($getProductRequested = $getProductRequestedResult->fetch_assoc()) {
                $getProductRequestedArray[$getProductRequested['product_name']] = $getProductRequested['totalProduct']; 
                $totalProductRequested = $getProductRequested['totalProduct'] + $totalProductRequested;
              
            }
        }
    } catch (Throwable $e) {
        echo mysqli_error($conn);
        echo $e;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-dashboard.css" rel="stylesheet">
    <title>Merchandise | Dashboard</title>
</head>
<body>
    <?php include ('header.php'); ?>

    <main>
        <?php include ('top_section.php'); ?>

        <section class="sect-admin">
            <div class="container row mx-auto">
                <h4 class="title dashboard-req">Request</h4>
                <div class="req-data chart row">
                    <canvas id="myChart" style="height: 370px; width: 100%; margin: auto;"></canvas>
                    <div class="btn-filter-date-req-chart" style="text-align: center">
                        <button type="button" class="btn btn-default-style" onclick="dateLineFilter('30')">Last 30 Days</button>
                        <button type="button" class="btn btn-default-style" onclick="dateLineFilter('90')">Last 90 Days</button>
                        <button type="button" class="btn btn-default-style" onclick="dateLineFilter('180')">Last 180 Days</button>
                    </div>
                </div>
                <div class="req-data row justify-content-center">
                    <div class="col-lg-2 col-md-3 col-sm-4 data">
                        <h6>Total Pending</h6>
                        <span style="color: #282929"><?php echo (empty($getRequestStatusArray['0'])) ? '0' : $getRequestStatusArray['0'] ?></span>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 data">
                        <h6>Total Accepted</h6>
                        <span style="color: var(--main-color)"><?php echo (empty($getRequestStatusArray['1'])) ? '0' : $getRequestStatusArray['1'] ?></span>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 data">
                        <h6>Total Rejected</h6>
                        <span style="color: #d61f26"><?php echo (empty($getRequestStatusArray['-1'])) ? '0' : $getRequestStatusArray['-1'] ?></span>
                    </div>
                </div>
            </div>
            <div class="container row mx-auto">
                <h4 class="title dashboard-prod">Product</h4>
                <div class="req-data row justify-content-center">
                    <div class="col-lg-2 col-md-3 col-sm-4 data">
                        <h6>Total Item</h6>
                        <span><?php echo $totalItem ?></span>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 data">
                        <h6>Total Item Requested</h6>
                        <span><?php echo $totalProductRequested?></span>
                    </div>
                </div>
                <div class="req-data-chart row">
                    <div class="chart-cate col">
                        <h6>Total Item In Each Category</h6>
                        <canvas id="id-totalItemChart" style="height: 270px; width: 270px; margin: auto;"></canvas>
                    </div>
                    <div class="chart-return col">
                        <h6>Total Item Requested</h6>
                        <canvas id="id-totalItemRequestedChart" style="height: 270px; width: 100%; margin: auto;"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    
    <script  src="js/chart.umd.js"></script> <!-- Chart JS v4.4.2 -->
    <script src="js/chartjs-adapter-moment.min.js"></script> <!-- Chart JS Adapter Moment v1.0.1  -->
    <script src="js/chartjs-adapter-date-fns.bundle.min.js"></script> <!-- Chart JS Adapter Date v3.0.0 -->
    <script>
            let requestData = [];
            let requestLabels = [];

            let itemCategoryData = [];
            let itemCategoryLabels = [];

            let itemRequestedData = [];
            let itemRequestedLabels = [];

            <?php 
                if (isset($getRequestArray)) {
                    foreach ($getRequestArray as $date => $total) {
                        echo 'requestData.push(' . $total . '); ';
                        $dateStr = strval($date);
                        echo 'requestLabels.push(\'' . $dateStr . '\');';
                    }
                }

                if (isset($getProductArray)) {
                    foreach($getProductArray as $category => $products) {
                        echo 'itemCategoryLabels.push(\'' . $category . '\'); ';
                        echo 'itemCategoryData.push(\'' . count($products) . '\'); '; 
                    }
                } 

                if (isset($getProductRequestedArray)) {
                    foreach($getProductRequestedArray as $product => $quantity) {
                        
                        echo 'itemRequestedLabels.push(\'' . $product . '\'); '; 
                        echo 'itemRequestedData.push(\'' . $quantity . '\'); '; 
                    }
                }

            ?>

            
        let updateChart;

        function handlerUpdateChart(filter) {
            updateChart(filter);
        }

        window.onload = () => {
            const ctx = document.getElementById('myChart');
            const ctxTotalItem = document.getElementById('id-totalItemChart');
            const ctxTotalRequestedItem = document.getElementById('id-totalItemRequestedChart');

            new Chart(ctxTotalRequestedItem, {
                type: 'bar',
                data: {
                    labels: itemRequestedLabels,
                    datasets: [{
                        label: 'Product',
                        data: itemRequestedData,
                        borderWidth: 1,
                        backgroundColor: 'rgba(214,31,38, 0.3)',
                        borderColor: '#282929'
                    }]

                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    label = 'Total Requested: ' + context.raw;
                                    return label;
                                }
                            }
                        }
                    },
                    ticks: {
                        precision: 0
                    }
                }
            });

        
            new Chart(ctxTotalItem, {
                type: 'pie',
                data: {
                    labels: itemCategoryLabels,
                    datasets: [{
                        data: itemCategoryData,
                        backgroundColor: [
                            '#fcdb27',
                            '#d61f26',
                            '#282929',
                            '#FFBF29',
                            '#F0E68C',
                            '#FFFF00',
                            '#FFD700',
                            '#DBD56E',
                            '#88AB75',
                            '#2D93AD',
                            '#7D7C84',
                            '#DE8F6E',
                            '#FF7F11',
                            '#3A1772',
                            '#918EF4',
                            '#EF8354',
                            '#2D3142',
                            '#6A0136',
                            '#BFAB25',
                            '#B81365',
                            '#380036',
                            '#01BAEF',
                            '#E84855',
                            '#B56B45',
                            '#CEFF1A',
                            '#414066',
                            '#FF4365',
                            '#00D9C0',
                            '#B30089',
                            '#0E402D',
                            '#9FCC2E',
                            '#FF4E00',
                            '#EC9F05',
                            '#B0DB43',
                            '#BCE7FD'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || 'the fuck';
                                    label = label + ': ' + context.parsed + ' item(s)';


                                    return label;
                                }
                            }
                        }
                    }
                },
            });

            const requestLineChart = new Chart(ctx, {
                animationEnabled: true,
                type: 'line',   
                data: {
                    labels:  requestLabels,
                    datasets: [{
                        label: 'Total request in the last 180 days',
                        borderColor: "#d61f26",
                        borderWidth: 1,
                        data: requestData,
                        borderWidth: 1,
                        tension: 0.3
                    }]
                },
                options: {
                    animation: {
                        x: {
                            duration: 1500,
                            from: 500
                        },
                        y: {
                            duration: 1000,
                            from:1000 
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = 'Total Request';

                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat().format(context.parsed.y) + " request(s)";
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    ticks: {
                        precision: 0
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                parser: 'yyyy-MM-dd'
                            }
                        }
                    }
                }
            });

            updateChart = (filter, text) => {
                const todayDate = new Date();
                let priorDate;
                let tempLabelArray = [];
                let tempDataArray = [];

                switch(filter) {
                case '30':
                    requestLineChart.data.datasets[0].label = 'Total request in the last 30 days';
                    priorDate = new Date(new Date().setDate(todayDate.getDate() - 30));
                    priorDate.setHours(0,0,0,0);

                    
                    

                    for (let i = 0; i < requestLabels.length; i++) {
                        let indexDate = new Date(requestLabels[i]);
                        indexDate.setHours(0,0,0,0);
                        
                        if (indexDate > priorDate) {
                            tempLabelArray.push(requestLabels[i]);
                            tempDataArray.push(requestData[i]);
                        } else {
                            break;
                        }
                    }
                  
                    requestLineChart.data.labels = tempLabelArray;
                    requestLineChart.data.datasets[0].data = tempDataArray;

                    break;

                case '90':
                    requestLineChart.data.datasets[0].label = 'Total request in the last 90 days';
                    priorDate = new Date(new Date().setDate(todayDate.getDate() - 90));
                    priorDate.setHours(0,0,0,0);

                    for (let i = 0; i < requestLabels.length; i++) {
                        let indexDate = new Date(requestLabels[i]);
                        indexDate.setHours(0,0,0,0);
                        
                        if (indexDate > priorDate) {
                            tempLabelArray.push(requestLabels[i]);
                            tempDataArray.push(requestData[i]);
                        } else {
                            break;
                        }
                    }
                  
                    requestLineChart.data.labels = tempLabelArray;
                    requestLineChart.data.datasets[0].data = tempDataArray;
                    break;

                case '180':
                    requestLineChart.data.datasets[0].label = 'Total request in the last 180 days';
                    priorDate = new Date(new Date().setDate(todayDate.getDate() - 180));
                    priorDate.setHours(0,0,0,0);

                    for (let i = 0; i < requestLabels.length; i++) {
                        let indexDate = new Date(requestLabels[i]);
                        indexDate.setHours(0,0,0,0);
                        
                        if (indexDate > priorDate) {
                            tempLabelArray.push(requestLabels[i]);
                            tempDataArray.push(requestData[i]);
                        } else {
                            break;
                        }
                    }
                  
                    requestLineChart.data.labels = tempLabelArray;
                    requestLineChart.data.datasets[0].data = tempDataArray;
                    break;

                default:
                    return;
                    break;
                    
            }

            requestLineChart.update();
            }

        }

        function dateLineFilter(filter) {
            handlerUpdateChart(filter);
        }

    </script>
    <?php include 'footer.php' ?>
</body>
</html>