
function chart() {
  return $.ajax({
              url:baseURL+'management/chart_prep',
              method:"GET",
              async: !1
  });
}

var data = chart();
var chartdata = null;

data.success(function(result)
              {
                chartdata = JSON.parse(result);
              });

  var lineChartData = {
      labels: [chartdata[0], chartdata[4], chartdata[8], chartdata[12], chartdata[16], chartdata[20], "Today"],
      datasets: [{
        label: 'SumCoins '+today_coins,
        borderColor: window.chartColors.red,
        backgroundColor: window.chartColors.red,
        fill: false,
        data: [
          chartdata[1],
          chartdata[5],
          chartdata[9],
          chartdata[13],
          chartdata[17],
          chartdata[21],
          today_coins
        ],
        yAxisID: 'y-axis-1',
      }, {
        label: 'Cuban-Link '+today_links,
        borderColor: window.chartColors.blue,
        backgroundColor: window.chartColors.blue,
        fill: false,
        data: [
          chartdata[2],
          chartdata[6],
          chartdata[10],
          chartdata[14],
          chartdata[18],
          chartdata[22],
          today_links
        ],
        yAxisID: 'y-axis-1'
      }, 
      {
        label: 'Mineralie '+today_rocks,
        borderColor: window.chartColors.red,
        backgroundColor: window.chartColors.red,
        fill: false,
        data: [
          chartdata[3],
          chartdata[7],
          chartdata[11],
          chartdata[15],
          chartdata[19],
          chartdata[23],
          today_rocks
        ],
        yAxisID: 'y-axis-1',
      }]
    };

    window.onload = function() {
      var ctx = document.getElementById('mycanvas').getContext('2d');
      window.myLine = Chart.Line(ctx, {
        data: lineChartData,
        options: {
          responsive: true,
          hoverMode: 'index',
          stacked: false,
          title: {
            display: true,
            text: 'Currency prices for a past week'
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1',
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',

              // grid line settings
              gridLines: {
                drawOnChartArea: false, // only want the grid lines for one axis to show up
              },
            }],
          }
        }
      });
    };