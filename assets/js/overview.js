$(document).ready(function () {
    var dom = document.getElementById("transactions");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    app.title = 'Transactions';

    option = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        color: ["#57BE65", "#ff1a1a"],
        series: [
            {
                name: 'Transactions',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '16',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [
                    {value: totalIncome, name: 'Income'},
                    {value: totalReversals, name: 'Reversals'}
                ]
            }
        ]
    };
    ;
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }


// graph
    var dom = document.getElementById("monthly");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    var xAxisData = [];
    var data1 = [];
    var data2 = [];
    for (var i = 1; i < 31; i++) {
        xAxisData.push(i + ' Jan');
        data2.push(Math.random());
        data1.push(Math.random() * -1);
    }

    option = {
        legend: {
            data: ['Reversals (' + currency + ')', 'Income (' + currency + ')'],
            align: 'left'
        },
        tooltip: {},
        xAxis: {
            data: labels,
            silent: false,
            splitLine: {
                show: false
            }
        },
        yAxis: {},
        series: [{
            name: 'Reversals (' + currency + ')',
            type: 'bar',
            stack: 'transactions',
            itemStyle: {
                normal: {
                    barBorderRadius: 50,
                    color: "#ff1a1a"
                }
            },
            data: expenses,
            animationDelay: function (idx) {
                return idx * 10;
            }
        }, {
            name: 'Income (' + currency + ')',
            type: 'bar',
            stack: 'transactions',
            barMaxWidth: 10,
            itemStyle: {
                normal: {
                    barBorderRadius: 50,
                    color: "#13A54E"
                }
            },
            data: income,
            animationDelay: function (idx) {
                return idx * 10 + 100;
            }
        }],
        animationEasing: 'elasticOut',
        animationDelayUpdate: function (idx) {
            return idx * 5;
        }
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

// graph-b2c
    var dombc = document.getElementById("monthly_disbursement");
    var myChart2 = echarts.init(dombc);
    var app = {};
    option2 = null;

    option2 = {
        xAxis: {
            type: 'category',
            data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line'
        }]
    };
    if (option2 && typeof option2 === "object") {
        myChart2.setOption(option2, true);
    }
});

$(function () {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('.reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('.reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);

});


$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
    $(".reports-title").text(picker.chosenLabel);
    server({
        url: reportsUrl,
        data: {
            "from": picker.startDate.format('YYYY-MM-DD'),
            "to": picker.endDate.format('YYYY-MM-DD')
        },
        loader: true
    });
});

function setTransactions(html) {

    $('#transactions_server').html(html);
}

function reports(reports) {

    $(".reports-income").text(reports.income.total);
    $(".income-count").text(reports.income.count + " Trns.");
    $(".reports-expenses").text(reports.reversals.total);
    $(".expenses-count").text(reports.reversals.count + " Trns.");

}