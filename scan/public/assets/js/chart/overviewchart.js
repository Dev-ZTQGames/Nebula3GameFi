
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
    createRadialBarChart("#d2c_overview_chart_1");
    createRadialBarChart("#d2c_overview_chart_2");
    createRadialBarChart("#d2c_overview_chart_3");
});

// overview chart
(() => {
    "use strict";
    const Trends = document.querySelector("#d2c_overview_chart") ?? "";

    if (Trends == "") {
        return false;
    } else {
        var options = {
            series: [{
                data: [
                ['2023-05-01', 150],
                ['2023-05-02', 160],
                ['2023-05-03', 170],
                ['2023-05-04', 161],
                ['2023-05-05', 167],
                ['2023-05-06', 162],
                ['2023-05-07', 161],
                ['2023-05-08', 152],
                ['2023-05-09', 141],
                ['2023-05-10', 144],
                ['2023-05-11', 154],
                ['2023-05-12', 166],
                ['2023-05-13', 176],
                ['2023-05-14', 187],
                ['2023-05-15', 198],
                ['2023-05-16', 210],
                ['2023-05-17', 196],
                ['2023-05-18', 207],
                ['2023-05-19', 200],
                ['2023-05-20', 187],
                ['2023-05-21', 192],
                ['2023-05-22', 204],
                ['2023-05-23', 193],
                ['2023-05-24', 204],
                ['2023-05-25', 193],
                ['2023-05-26', 204],
                ['2023-05-27', 208],
                ['2023-05-28', 196],
                ['2023-05-29', 193],
                ['2023-05-30', 178],
                ['2023-05-31', 204],
                ['2023-06-01', 218],
                ['2023-06-02', 211],
                ['2023-06-03', 218],
                ['2023-06-04', 216],
                ['2023-06-05', 197],
                ['2023-06-06', 190],
                ['2023-06-07', 179],
                ['2023-06-08', 172],
                ['2023-06-09', 158],
                ['2023-06-10', 159],
                ['2023-06-11', 147],
                ['2023-06-12', 152],
                ['2023-06-13', 137],
                ['2023-06-14', 136],
                ['2023-06-15', 123],
                ['2023-06-16', 112],
                ['2023-06-17', 99],
                ['2023-06-18', 100],
                ['2023-06-19', 95],
                ['2023-06-20', 105],
                ['2023-06-21', 116],
                ['2023-06-22', 125],
                ['2023-06-23', 124],
                ['2023-06-24', 133],
                ['2023-06-25', 129],
                ['2023-06-26', 116],
                ['2023-06-27', 119],
                ['2023-06-28', 109],
                ['2023-06-29', 115],
                ['2023-06-30', 111],
                ['2023-07-01', 96],
                ['2023-07-02', 104],
                ['2023-07-03', 102],
                ['2023-07-04', 116],
                ['2023-07-05', 126],
                ['2023-07-06', 117],
                ['2023-07-07', 130],
                ['2023-07-08', 124],
                ['2023-07-09', 126],
                ['2023-07-10', 131],
                ['2023-07-11', 143],
                ['2023-07-12', 130],
                ['2023-07-13', 116],
                ['2023-07-14', 118],
                ['2023-07-15', 122],
                ['2023-07-16', 132],
                ['2023-07-17', 126],
                ['2023-07-18', 136],
                ['2023-07-19', 123],
                ['2023-07-20', 112],
                ['2023-07-21', 116],
                ['2023-07-22', 113],
                ['2023-07-23', 109],
                ['2023-07-24', 99],
                ['2023-07-25', 100],
                ['2023-07-26', 93],
                ['2023-07-27', 85],
                ['2023-07-28', 79],
                ['2023-07-29', 64],
                ['2023-07-30', 79],
                ]
            }],
            chart: {
                type: 'area',
                foreColor: '#85839D',
                stacked: false,
                height: 270,
                zoom: {
                    type: 'x',
                    enabled: true,
                    autoScaleYaxis: true,
                },
                toolbar: {
                    show: false,
                },
                fontFamily: 'Lato',
            },
            stroke:{
                width: 2
            },
            colors: ['#001216'],
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 0,
            },
            grid:{
                borderColor: '#2c5e670d',
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: .5,
                    opacityTo: .5,
                    stops: [100, 100, 100],
                },
            },
            xaxis: { 
                type: 'datetime',
                labels: {
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 700,
                    },
                },
            },
            yaxis:{
                labels: {
                    formatter: function (y) {
                        return "$"+ y.toFixed(0) + "k";
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Lato',
                        fontWeight: 700,
                    },
                },
            },
            responsive: [{
                breakpoint: 767,
                options: {
                    chart: {
                        height: 250,
                    },
                },
            }]
        };
        // Create the chart
        var chart = new ApexCharts(Trends, options);
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