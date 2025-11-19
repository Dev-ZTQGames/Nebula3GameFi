
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
    createRadialBarChart("#d2c_currency_chart_1");
    createRadialBarChart("#d2c_currency_chart_2");
    createRadialBarChart("#d2c_currency_chart_3");
    createRadialBarChart("#d2c_currency_chart_4");
});


// <!--
//     Template Name: {{CryptoTrakX}}
//     Template URL: {{https://designtocodes.com/product/cryptotrakx-tailwind-crypto-dashboard}}
//     Description: {{Take Control of Your Crypto Portfolio with CryptoTrakx Tailwind Crypto Dashboard! Keep track of all your cryptocurrencies with ease using it.}}
//     Author: DesignToCodes
//     Author URL: https://www.designtocodes.com
//     Text Domain: {{ CryptoTrakX }} 
// -->