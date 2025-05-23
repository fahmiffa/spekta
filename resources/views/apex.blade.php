@push('js')
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
    let savedTheme = localStorage.getItem("theme") || "light";
    document.documentElement.setAttribute("data-bs-theme", savedTheme);

    function isDarkMode() {
        return document.documentElement.getAttribute("data-bs-theme") === "dark";
    }

    function chart(isDark) {
        return {
            title: {
                text: 'Grafik Permohonan',
                align: 'left',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color: isDark ? '#fff' : '#333'
                }
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            series: [
                {
                    name: "Permohonan Total",
                    data: @json($chart['counts']),
                },
                {
                    name: "Permohonan Dalam Proses",
                    data: @json($chart['no']),
                },
                {
                    name: "Permohonan Selesai",
                    data: @json($chart['done']),
                },
            ],
            chart: {
                type: 'bar',
                height: 350,
                background: 'transparent'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    dataLabels: {
                        position: 'top',
                    },
                },
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: [isDark ? "#fff" : "#304758"]
                },
                 formatter: function (val) {
                    return val === 0 ? "" : val; 
                }
            },
            colors: isDark ? ['#ff5722', '#0dcaf0', '#5ddab4'] : ['#ff5722', '#0dcaf0', '#5ddab4'],
            xaxis: {
                categories: @json($chart['months']),
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val;
                    }
                }
            }
        };
    }

    var chartProfileVisit = new ApexCharts(
        document.querySelector("#chart-profile-visit"),
        chart(isDarkMode())
    );
    chartProfileVisit.render();

    document.getElementById("toggle-dark").addEventListener("click", function () {
        let htmlEl = document.documentElement;
        let currentTheme = htmlEl.getAttribute("data-bs-theme");
        let newTheme = currentTheme === "dark" ? "light" : "dark";

        htmlEl.setAttribute("data-bs-theme", newTheme);
        localStorage.setItem("theme", newTheme);

        chartProfileVisit.updateOptions(chart(isDarkMode()));
    });
</script>

@endpush