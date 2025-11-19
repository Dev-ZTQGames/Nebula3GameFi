// // Market data chart 1
(() => {
    'use strict';
    const Chart = document.querySelector('#d2c_market_chart_1') ?? '';
    if (Chart == '') {
        return false;
    } else {
        var options = {
            series: [
                {
                    name: '2024 Users',
                    data: [-20, 20, 45, 32, 38, 24, 16,35,11,18,-20,20],
                },
            ],
            colors: ['#001216'],
            chart: {
                type: 'area',
                foreColor: '#98AAAA',
                height: 90,
                toolbar: {
                    show: false,
                },
                sparkline:{
                    enabled:true
                },
                fontSize: '14px',
                fontFamily: 'Lato',
                fontWeight: 600,
            },
            dataLabels: {
                enabled: false,
            },
            grid:{
                borderColor: '#EAF0F0',  
                show: false
            },
            fill:{
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [100, 100, 100],
                },
            },
            stroke: {
                curve: 'smooth',
                width: 0
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                show: false,
                fontSize: '14px',
                fontFamily: 'Lato',
                fontWeight: 600,
            },
            yaxis:{
                show: false,
                labels: {
                    formatter: function (y) {
                        return y.toFixed(0) + "k";
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 600,
                    },
                },
            },
            xaxis: {
                // type: 'datetime',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
                labels: {
                    show: false,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 600,
                    },
                },
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm',
                },
            },
        };

        var chart = new ApexCharts(Chart, options);
        chart.render();
    }
})();


document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    function createRadialBarChart(id) {
        const RadialBar = document.querySelector(id);

        if (RadialBar == null) {
            return;
        }

        var options = {
            series: [
                {
                    name: '2024 Users',
                    data: [11, 32, 45, 32, 34, 52, 41,35,27,36,30,42],
                },
            ],
            colors: ['#001216'],
            chart: {
                type: 'area',
                foreColor: '#98AAAA',
                height: 70,
                toolbar: {
                    show: false,
                },
                sparkline:{
                    enabled:true
                },
                fontSize: '14px',
                fontFamily: 'Lato',
                fontWeight: 600,
            },
            dataLabels: {
                enabled: false,
            },
            grid:{
                borderColor: '#EAF0F0',  
                show: false
            },
            fill:{
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0,
                    opacityTo: 0,
                    stops: [0, 90, 100],
                },
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                show: false,
                fontSize: '14px',
                fontFamily: 'Lato',
                fontWeight: 600,
            },
            yaxis:{
                show: false,
                labels: {
                    formatter: function (y) {
                        return y.toFixed(0) + "k";
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 600,
                    },
                },
            },
            xaxis: {
                // type: 'datetime',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
                labels: {
                    show: false,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 600,
                    },
                },
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm',
                },
            },
        };

        var chart = new ApexCharts(RadialBar, options);
        chart.render();
    }
    createRadialBarChart("#d2c_market_chart_2");
    createRadialBarChart("#d2c_market_chart_3");
    createRadialBarChart("#d2c_market_chart_4");
    createRadialBarChart("#d2c_market_chart_5");
    createRadialBarChart("#d2c_market_chart_6");
});

// Market Sentiment chart
(() => {
    'use strict';
    const Chart = document.querySelector('#d2c_market_sentiment_chart') ?? '';

    if (Chart == '') {
        return false;
    } else {
        var options = {
            series: [82],
            chart: {
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                offsetY: -5,
                // height:350
            },
            fill:{
                opacity:1
            },
            plotOptions: {
                radialBar: {
                  startAngle: -100,
                  endAngle: 100,
                  track: {
                    background: "#2c5e6714",
                    strokeWidth: '100%',
                    margin: 0, // margin is in pixels
                    dropShadow: {
                      enabled: false,
                    }
                  },
                  dataLabels: {
                    name: {
                      show: false
                    },
                    value: {
                      offsetY: 0,
                      fontSize: '22px'
                    }
                  }
                }
              },
            stroke: {
                lineCap: 'round',
            },
            colors:['#001216'],
            grid: {
                padding: {
                    top: -10
                },
            },
            dataLabels:{
                colors: '#001216',
            },
        };

        var chart = new ApexCharts(Chart, options);
        chart.render();
    }
})();

// <!--
//     Template Name: {{CryptoTrakX}}
//     Template URL: {{https://designtocodes.com/product/cryptotrakx-tailwind-crypto-dashboard}}
//     Description: {{Take Control of Your Crypto Portfolio with CryptoTrakx Tailwind Crypto Dashboard! Keep track of all your cryptocurrencies with ease using it.}}
//     Author: DesignToCodes
//     Author URL: https://www.designtocodes.com
//     Text Domain: {{ CryptoTrakX }} 
// -->