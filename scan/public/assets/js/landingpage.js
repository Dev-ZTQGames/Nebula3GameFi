

const mobileMenu = document.getElementById('mobile-menu');
const toggleMenu = () => mobileMenu.classList.toggle('hidden');

// Function to check if click occurred outside the mobile menu
const clickOutsideMenu = (event) => {
    if (!mobileMenu.contains(event.target) && !document.getElementById('mobile-menu-btn').contains(event.target)) {
        mobileMenu.classList.add('hidden');
    }
};

document.getElementById('mobile-menu-btn').addEventListener('click', toggleMenu);
document.getElementById('close-drawer-btn').addEventListener('click', () => mobileMenu.classList.add('hidden'));
document.querySelectorAll('#mobile-menu .nav_link').forEach(link => link.addEventListener('click', toggleMenu));

// Add event listener to document body to check for clicks outside the mobile menu
document.body.addEventListener('click', clickOutsideMenu);

// Toggle the visibility of the submenu on Home link click (Desktop)
document.getElementById('desktop-home-link').addEventListener('click', function () {
    const submenu = document.getElementById('desktop-submenu');
    submenu.classList.toggle('hidden');
});

// Hide the submenu when clicking outside of #desktop-home-link or #desktop-submenu
document.addEventListener('click', function (event) {
    const desktopHomeLink = document.getElementById('desktop-home-link');
    const desktopSubmenu = document.getElementById('desktop-submenu');
    const mobileMenu = document.getElementById('mobile-menu');

    // Check if the click occurred outside of desktop-home-link and desktop-submenu
    if (!event.target.closest('#desktop-home-link') && !event.target.closest('#desktop-submenu')) {
        // If the click occurs outside of desktop-home-link and desktop-submenu
        desktopSubmenu.classList.add('hidden');
    }
});

// Toggle the visibility of the submenu on Home link click (Mobile)
document.getElementById('mobile-home-link').addEventListener('click', function () {
    const submenu = document.getElementById('mobile-submenu');
    submenu.classList.toggle('hidden');
    // Show the drawer
    document.getElementById('mobile-menu').classList.remove('hidden');
});

// nav link js
document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll("#d2c_navigation .nav_link");

    function activateNavLink(id) {
        navLinks.forEach(navLink => {
            if (navLink.getAttribute("href").substring(1) === id) {
                navLink.classList.add("active");
            } else {
                navLink.classList.remove("active");
            }
        });
    }

    function getOffset() {
        // Define different offsets for different devices
        const desktopOffset = 53;
        const tabletOffset = 66;
        const mobileOffset = 40;

        // Determine the device width
        const screenWidth = window.innerWidth;

        // Set offset based on the device width
        if (screenWidth >= 1024) { // Desktop
            return desktopOffset;
        } else if (screenWidth >= 768) { // Tablet
            return tabletOffset;
        } else { // Mobile
            return mobileOffset;
        }
    }

    navLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                const offset = targetElement.offsetTop - getOffset(); // Adjusted offset
                window.scrollTo({
                    top: offset,
                    behavior: "smooth"
                });
            }
        });
    });

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activateNavLink(entry.target.id);
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(section => observer.observe(section));

    // Initialize active link based on initial scroll position
    const initialSectionInView = Array.from(sections).find(section =>
        section.getBoundingClientRect().top >= 0 && section.getBoundingClientRect().top <= window.innerHeight
    );
    if (initialSectionInView) {
        activateNavLink(initialSectionInView.id);
    }
});

// Partner slider
(() => {
    // Silk Carousel slick
    function slickCarousel() {

        // partner slider
        $('.d2c_Partner_slider').slick({
            slidesToShow: 6 ,
            slidesToScroll: 1,
            autoplay: true,
            arrows: false,
            speed: 2000,
            autoplaySpeed: 1000,
            dots: false,
            infinite: true,
            // centerMode: true,
            // centerPadding: '60px',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
            ]
        });

        // testimonial slider
    $('.d2c_testimonial_slider').slick({
            slidesToShow: 3 ,
            slidesToScroll: 1,
            autoplay: true,
            arrows: false,
            speed: 1500,
            autoplaySpeed: 3000,
            dots: true,
            infinite: true,
            responsive: [
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                },
            ]
        });

    }
    slickCarousel(); 
})();

// token-metrics chart
(() => {
    'use strict';
    const Chart = document.querySelector('#d2c_token_metrics_chart') ?? '';

    if (Chart == '') {
        return false;
    } else {
        var options = {
            chart: {
                type: 'donut',
                width: '100%',
                height:350,
                fontFamily: 'Lato',
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '18px',
                    fontFamily: 'Lato',
                    fontWeight: 'bold',
                    colors: ['#fff']
                },
                formatter: function (val, opts) {
                    return val + '%'
                },
                dropShadow: {
                    enabled: false,
                },
                background: {
                    enabled: false,
                }
            },
            colors: ["#377783", "#2C5E67", "#277685", "#19869b"],
            title: {
                show:false,
            },  
            stroke:{
                width:0
            },
            fill:{
                opacity:1
            },
            series: [30,30,10,30],
            labels: ['Equity', 'Alternatives','Fixed Income','Other Income'],
            legend: {
                show:false,
            },
            tooltip: {
                fillSeriesColor: '#fff',
                theme: false,
                style: {
                    fontSize: '18px',
                    fontFamily: 'Lato',
                    fontWeight: 'bold',
                    colors: ['#fff']
                },
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                    },      
                },
            },
            responsive: [{
                breakpoint: 991,
                options: {
                    legend: {
                        position: 'bottom'
                    },
                    chart: {
                        height:250,
                    },
                    dataLabels: {
                        style: {
                            fontSize: '14px',
                        },
                    },
                }
            }]
        };

        var chart = new ApexCharts(Chart, options);
        chart.render();
    }
})();

// allocation of funds chart
document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    const lineChart = document.querySelector("#d2c_allocation_chart") ?? "";

    if (lineChart == "") {
        return false;
    } else {
        var options = {
            series: [{
                name:['Ratio'],
                data: [65, 60, 30, 45]
            }],
            chart: {
                type: 'bar',
                height: 200,
                toolbar: {
                    show: false,
                },
                foreColor: '#98AAAA',
                fontFamily: 'Lato',
                sparkline:{
                    enabled:true
                }
            },
            colors: ["#377783", "#2C5E67", "#277685", "#19869b"],
            plotOptions: {
                bar: {
                    dataLabels: {
                        position: 'top',
                    },
                    distributed: true,
                    borderRadius: 6,
                    barHeight: '50%',
                    horizontal: true,
                }
            },
            grid:{
                show:false,
                borderColor:'#fff'
            },
            fill:{
                opacity:1
            },
            tooltip: {
                fillSeriesColor: '#ffffff',
                // theme: false,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Lato',
                    fontWeight: 'bold',
                    colors: ['#001216']
                },
            },
            dataLabels: {
                enabled: true,
                offsetX: 50,
                style: {
                    fontSize: '18px',
                    fontWeight: 700,
                    fontFamily: 'Lato',
                    colors: ['#ffffff']
                },
                formatter: function (val, opts) {
                    return val + '%';
                },
            },
            xaxis: {
                categories: ['Ecosystem', 'Advisors', 'Private Sale', 'Public Sale'],
                labels: {
                    show: false,
                    style: {
                        colors: '#204C4C',
                        fontSize: '14px',
                        fontWeight: 700,
                        fontFamily: 'Lato',
                    }
                },
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
            },
            yaxis: {
                labels: {
                    show:false,
                    style: {
                        colors: '#204C4C',
                        fontSize: '14px',
                        fontWeight: 700,
                        fontFamily: 'Lato',
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    dataLabels: {
                        enabled: true,
                        offsetX: 0,
                        style: {
                            colors: ['#fff'],
                            fontSize: '10px',
                        },
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'center',
                            },
                        }
                    },
                }
            }],
        };

        var chart = new ApexCharts(lineChart, options);
        chart.render();
    }
});

// <!--
//     Template Name: {{CryptoTrakX}}
//     Template URL: {{https://designtocodes.com/product/cryptotrakx-tailwind-crypto-dashboard}}
//     Description: {{Take Control of Your Crypto Portfolio with CryptoTrakx Tailwind Crypto Dashboard! Keep track of all your cryptocurrencies with ease using it.}}
//     Author: DesignToCodes
//     Author URL: https://www.designtocodes.com
//     Text Domain: {{ CryptoTrakX }} 
// -->