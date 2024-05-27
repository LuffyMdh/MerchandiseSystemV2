

window.onload = () => {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title:{
            text: "Company Revenue by Year"
        },
        axisY: {
            title: "Revenue in USD",
            valueFormatString: "#0,,.",
            suffix: "mn",
            prefix: "$"
        },
        data: [{
            type: "spline",
            markerSize: 5,
            xValueFormatString: "YYYY",
            yValueFormatString: "$#,##0.##",
            xValueType: "dateTime",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
     
    chart.render();
}