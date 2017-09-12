/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var chatData;
app.controller('dashboardController', function ($scope, $http, $timeout) {
    $scope.name = '';
    $scope.availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme"
    ];
    var poll = function () {
        $timeout(function () {
            
            //Active Call
            $http({
                method: "GET",
                url: baseUrl + "get-active-count"
            }).then(function (response) {
                $scope.liveStstus = response.data; // get data from json
                console.log($scope.liveStstus);
                poll();
            }, function (response) {
                $scope.liveStstus = "Something went wrong";
            });
            
            //Completed Call
            $http({
                method: "GET",
                url: baseUrl + "get-completed-call"
            }).then(function (response) {
                $scope.completedStstus = response.data; // get data from json
                console.log($scope.completedStstus);
                poll();
            }, function (response) {
                $scope.completedStstus = "Something went wrong";
            });
            
        }, 3000);
    };
    poll();

    var chartConfig = {
        chart: {
            type: 'line',
            backgroundColor: "#181C21",
            color: "#FFF"
        },
        title: {
            text: 'Stacked column chart',
            style: {"color": "#999"}
        },
        navigation: {
            buttonOptions: {
            },
            menuStyle: {"border": "none", "background": "#FFF", "padding": "5px 0"}
        },
        xAxis: {
            categories: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Counts'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'center',
            x: 30,
            verticalAlign: 'bottom',
            y: 22,
            itemStyle: {
                "color": "#999",
                "cursor": "pointer",
                "fontSize": "12px",
                "fontWeight": "bold",
                "textOverflow": "ellipsis"},
            itemHoverStyle: {"color": "#FFF"},
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>' +
                        'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        },
        series: [{
                "name": "Connections",
                "data": [10, 30, 40, 25, 40, 15, 10, 50, 20, 40, 20, 20]
            }]
    };
    console.log($scope.chartData);
    $scope.chartConfig = chartConfig;
    $('#container').highcharts(chartConfig);
});

app.controller('tableController', function ($scope, $http, $timeout) {
    var poll = function () {
        $timeout(function () {
            $http({
                method: "GET",
                url: baseUrl + "get-table"
            }).then(function (response) {
                $('.loader').hide();
                $scope.usersTableData = response.data; // get data from json
                $keepGoing = true;
                angular.forEach($scope.usersTableData, function (value, key) {
                    if ($keepGoing) {
                        $scope.usersTableKey = value;
                        $keepGoing = false;
                    }
                });
                poll();
            }, function (response) {
                $scope.usersTableData = "Something went wrong";
            });
        }, 3000);
    };
    poll();
});