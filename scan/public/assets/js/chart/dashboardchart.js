
document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    
    function ActiveUserTrend(id) {
        const RadialBar = document.querySelector(id);

        if (RadialBar == null) {
            return;
        }

        function ActiveUserTrendDataLastWeek() {
            const today = new Date();
            const startDate = new Date(today);
            startDate.setDate(today.getDate() - 7); 

            const categories = [];
            const dauData = [];
            const mauData = [];

            let currentDate = startDate;
            let mauTotal = 0; 

            const getValues = [80, 100, 130, 160, 175, 185, 200];  // 예시: 최근 일주일간 DAU

            let index = 0; 

            while (currentDate < today) { 
                const formattedDate = currentDate.getDate() + " " + currentDate.toLocaleString('en-US', { month: 'short' });
                categories.push(formattedDate); 

                const dailyDau = getValues[index];

                dauData.push(dailyDau);

                // MAU는 DAU를 누적
                mauTotal += dailyDau; 
                mauData.push(mauTotal); 

                currentDate.setDate(currentDate.getDate() + 1);
                index++;
            }

            return { categories, dauData, mauData };
        }

        const { categories, dauData, mauData } = ActiveUserTrendDataLastWeek();

        var options = {
            series: [
                {
                    name: "DAU",
                    data: dauData
                },
                {
                    name: "MAU",
                    data: mauData
                }
            ],
            colors: ["#3434EA", "#A14BC9"],
            chart: {
                type: "bar",
                stacked: false, 
                height: 280,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            responsive: [{
                breakpoint: 720,
                options: {
                    chart: {
                        height: "200"
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 5,
                    borderRadiusApplication: "end",
                    borderRadiusWhenStacked: "last"
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false }
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
                fontSize: "14px",
                fontWeight: 400,
                markers: {
                    size: 5,
                    shape: "circle",
                    radius: 999,
                    strokeWidth: 0
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            yaxis: {
                title: false
            },
            grid: {
                yaxis: {
                    lines: { show: true }
                }
            },
            fill: { opacity: 1 },
            tooltip: {
                enabled: true,
                x: { show: false },
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            }
        };

        var chart = new ApexCharts(RadialBar, options);
        chart.render();
    }

    ActiveUserTrend("#ActiveUserTrend_chart");


    
    
    
    function TaskParticipantsTrend(id) {
        const chartEl = document.querySelector(id);
        if (!chartEl) return;

        function TaskParticipantsTrendDataLastWeek() {
            const today = new Date();
            const startDate = new Date(today);
            startDate.setDate(today.getDate() - 7); // 금일 제외하고 7일 전부터 시작

            const categories = [];
            const dauData = [];
            const mauData = [];
            const taskCompletedData = [];  
            const taskPerDAU = [];  
            const taskPerMAU = [];  

            let currentDate = startDate;
            let mauTotal = 0; 

            const getValues = [80, 100, 130, 160, 175, 185, 200];  // 예시: 최근 일주일간 DAU
            const spendValues = [50, 60, 70, 65, 75, 80, 85];  // 예시: 최근 일주일간 Task 완료 개수

            let index = 0;

            while (currentDate < today) {
                const formattedDate = currentDate.getDate() + " " + currentDate.toLocaleString('en-US', { month: 'short' });
                categories.push(formattedDate); 

                const dailyDau = getValues[index];
                const dailyTaskCompleted = spendValues[index];

                dauData.push(dailyDau);

                mauTotal += dailyDau; 
                mauData.push(mauTotal); 

                taskCompletedData.push(dailyTaskCompleted);

                const dailyTaskPerDAU = ((dailyTaskCompleted / dailyDau) * 100).toFixed(1); 
                const dailyTaskPerMAU = ((dailyTaskCompleted / mauTotal) * 100).toFixed(1); 

                taskPerDAU.push(dailyTaskPerDAU);
                taskPerMAU.push(dailyTaskPerMAU);

                currentDate.setDate(currentDate.getDate() + 1);
                index++;
            }

            return { categories, dauData, mauData, taskPerDAU, taskPerMAU, taskCompletedData };
        }

        const { categories, dauData, mauData, taskPerDAU, taskPerMAU, taskCompletedData } = TaskParticipantsTrendDataLastWeek();

        var options = {
            series: [
                {
                    name: "DAU",
                    data: dauData
                },
                {
                    name: "MAU",
                    data: mauData
                },
            ],
            colors: ["#3434EA", "#A14BC9"],
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
                fontSize: "14px",
                fontWeight: 400,
                markers: {
                    size: 5,
                    shape: "circle",
                    radius: 999,
                    strokeWidth: 0
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            chart: {
                height: 280,
                type: "area",
                toolbar: {
                    show: false
                }
            },
            responsive: [{
                breakpoint: 720,
                options: {
                    chart: {
                        height: "200"
                    }
                }
            }],
            fill: {
                gradient: {
                    enabled: true,
                    opacityFrom: 0.55,
                    opacityTo: 0
                }
            },
            stroke: {
                curve: "smooth",
                width: 2
            },
            markers: {
                size: 0
            },
            grid: {
                xaxis: {
                    lines: { show: false }
                },
                yaxis: {
                    lines: { show: true }
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                enabled: true,
                shared: true,
                /*x: {
                    formatter: function (val) {
                        var date = new Date(new Date().getFullYear(), new Date().getMonth(), val);
                        var formattedDate = date.getFullYear() + '/' + (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getDate().toString().padStart(2, '0');
                        return formattedDate;
                    }
                },*/
                x: { show: false },
                y: {
                    formatter: val => val + "%"
                }
            },
            xaxis: {
                type: "category",
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false }
            },
            yaxis: {
                labels: {
                    formatter: val => val + "%"
                },
                title: {
                    style: { fontSize: "0px" }
                }
            }
        };

        var chart = new ApexCharts(chartEl, options);
        chart.render();
    }

    TaskParticipantsTrend("#TaskParticipantsTrend_chart");


        
    
    
    function MonetizationEngine(id) {
        const RadialBar = document.querySelector(id);

        if (RadialBar == null) {
            return;
        }
        
        function MonetizationEngineDataLastWeek() {
            const today = new Date();
            const startDate = new Date(today);
            startDate.setDate(today.getDate() - 7);  // 금일 제외하고 7일 전부터 시작

            const categories = [];
            const cumulativeIAA = [];
            const cumulativeIAP = [];

            let currentDate = startDate;
            let totalIAA = 0;
            let totalIAP = 0;

            const getValues = [80, 100, 130, 160, 175, 185, 200];  // 예시: 최근 일주일간 획득한 IAA Users
            const spendValues = [40, 30, 50, 40, 55, 40, 70];  // 예시: 최근 일주일간 소모된 IAP Users

            let index = 0; 

            while (currentDate < today) {
                const formattedDate = currentDate.getDate() + " " + currentDate.toLocaleString('en-US', { month: 'short' });
                categories.push(formattedDate); 

                const dailyIAA = getValues[index];
                const dailyIAP = spendValues[index];

                totalIAA += dailyIAA;
                cumulativeIAA.push(totalIAA);

                totalIAP += dailyIAP;
                cumulativeIAP.push(totalIAP);

                currentDate.setDate(currentDate.getDate() + 1); 
                index++;
            }

            return { categories, cumulativeIAA, cumulativeIAP };
        }

        const { categories, cumulativeIAA, cumulativeIAP } = MonetizationEngineDataLastWeek();

        var options = {
            series: [
                {
                    name: "IAA Users",
                    data: cumulativeIAA
                },
                {
                    name: "IAP Users",
                    data: cumulativeIAP
                }
            ],
            colors: ["#3434EA", "#A14BC9"],
            chart: {
                type: "bar",
                stacked: true,
                height: 280,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            responsive: [{
                breakpoint: 720,
                options: {
                    chart: {
                        height: "200"
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 5,
                    borderRadiusApplication: "end",
                    borderRadiusWhenStacked: "last"
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: categories, 
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
                fontSize: "14px",
                fontWeight: 400,
                markers: {
                    size: 5,
                    shape: "circle",
                    radius: 999,
                    strokeWidth: 0
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            yaxis: {
                title: false
            },
            grid: {
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                enabled: true,
                x: { show: false },
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            }
        };

        var chart = new ApexCharts(RadialBar, options);
        chart.render();
    }

    MonetizationEngine("#MonetizationEngine_chart");

    
    
    
    function GetSpend(id) {
        const RadialBar = document.querySelector(id);

        if (RadialBar == null) {
            return;
        }

        function GetSpendDataLastWeek() {
            const today = new Date();
            const startDate = new Date(today);
            startDate.setDate(today.getDate() - 7);  // 금일 제외하고 7일 전부터 시작

            const categories = [];
            const Get = [];
            const Spend = [];
            const SpendRatio = [];

            let currentDate = startDate;

            const getValues = [80, 100, 130, 160, 175, 185, 200];  // 예시: 최근 일주일간 획득한 재화
            const spendValues = [40, 30, 50, 40, 55, 40, 70];  // 예시: 최근 일주일간 소모된 재화

            let index = 0; 

            let totalGet = 0;
            let totalSpend = 0;

            while (currentDate < today) {
                const formattedDate = currentDate.getDate() + " " + currentDate.toLocaleString('en-US', { month: 'short' });
                categories.push(formattedDate); 

                const dailyGet = getValues[index];
                const dailySpend = spendValues[index];

                totalGet += dailyGet;
                totalSpend += dailySpend;

                Get.push(totalGet);
                Spend.push(totalSpend);

                // Spend ratio 계산: (Spend/Get) * 100
                const dailySpendRatio = ((totalSpend / totalGet) * 100).toFixed(1);
                SpendRatio.push(dailySpendRatio);

                currentDate.setDate(currentDate.getDate() + 1);
                index++;
            }

            return { categories, Get, Spend, SpendRatio };
        }

        // 예시로 최근 일주일의 데이터를 생성
        const { categories, Get, Spend, SpendRatio } = GetSpendDataLastWeek();

        var options = {
            series: [
                {
                    name: "Get",
                    data: Get
                },
                {
                    name: "Spend",
                    data: Spend
                },
                {
                    name: "Spend ratio",
                    data: SpendRatio
                }
            ],
            colors: ["#3434EA", "#A14BC9", "#FF8533"],
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
                fontSize: "14px",
                fontWeight: 400,
                markers: {
                    size: 5,
                    shape: "circle",
                    radius: 999,
                    strokeWidth: 0
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            chart: {
                height: 280,
                type: "area",
                toolbar: {
                    show: false
                }
            },
            responsive: [{
                breakpoint: 720,
                options: {
                    chart: {
                        height: "200"
                    }
                }
            }],
            fill: {
                gradient: {
                    enabled: true,
                    opacityFrom: 0.55,
                    opacityTo: 0
                }
            },
            stroke: {
                curve: "smooth",
                width: ["2", "2"]
            },
            markers: {
                size: 0
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                enabled: true,
                shared: true,
                x: { show: false },
                y: {
                    formatter: function (val, { seriesIndex }) {
                        if (seriesIndex === 2) { // Spend ratio 시리즈만 % 표시
                            return val.toFixed(1) + "%";
                        }
                        return val;
                    }
                }
            },
            xaxis: {
                type: "category",
                categories: categories,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: false
            },
            yaxis: {
                title: {
                    style: {
                        fontSize: "0px"
                    }
                }
            }
        };

        var chart = new ApexCharts(RadialBar, options);
        chart.render();
    }

    GetSpend("#GetSpend_chart");

});

