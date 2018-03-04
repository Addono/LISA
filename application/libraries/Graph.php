<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Graph {

    public function includeJsLibrary(): string
    {
        return '<script src="' . base_url('node_modules/plotly.js/dist/plotly.min.js') . '" type="text/javascript"></script>';
    }

    public function getGraphForTransactions(array $transactionData)
    {
        $x = [];
        $y = [];

//        var_dump($transactionData);
//
//        $weekNr = $transactionData[0]['week'];
//        $yearNr = $transactionData[0]['year'];
//        foreach ($transactionData as $week) {
//            // Reset the week number counter if a new year is started.
//            if ($yearNr !== $week['year']) {
//                // Fill all missing weeks until the start of the year.
//                while(++$weekNr % 52 !== 1) {
//                    $x[] = "'" . $weekNr . " - " . $week['year'] . "'";
//                    $y[] = 0;
//                }
//
//                $weekNr = 1;
//                $yearNr = $week['year'];
//            }
//
//            // Fill all missing weeks.
//            for (; $weekNr < $week['week']; $weekNr++) {
//                $x[] = "'" . $weekNr . " - " . $week['year'] . "'";
//                $y[] = 0;
//            }
//
//            $x[] = "'" . $week['week'] . " - " . $week['year'] . "'";
//            $y[] = -$week['sum'];
//
//            $weekNr++;
//        }

        $keyedTransactionData = [];
        foreach ($transactionData as $row) {
            ['year' => $year, 'week' => $week, 'sum' => $sum] = $row;
            $keyedTransactionData[$year * 52 + $week - 1] = $row;
        }

        asort($keyedTransactionData);

        reset($keyedTransactionData);
        $lowestKey = key($keyedTransactionData);

        $date = new DateTime('now');
        $largestKey = $date->format('W') + $date->format('Y') * 52;

        for ($i = $lowestKey; $i < $largestKey; $i++) {
            $week = ($i % 52) + 1;
            $year = floor($i / 52);
            $x[] = "'" . $year . " - " . $week . "'";
            if (array_key_exists($i, $keyedTransactionData)) {
                $y[] = -$keyedTransactionData[$i]['sum'];
            } else {
                $y[] = 0;
            }
        }

        return '<script>
                var data = [
                    {
                        x: [' . implode(",", $x) . '],
                        y: [' . implode(",", $y) . '],
                        type: \'shatter\',
                        line: {shape: \'hvh\'}
                    }
                ];
                
                var layout = {
                    xAxis: {
                        min: 0
                    }
                };
                
                var d3 = Plotly.d3;
                var WIDTH_IN_PERCENT_OF_PARENT = 100;
                
                var gd3 = d3.select(\'#chart\')
                    .append(\'div\')
                    .style({
                        width: WIDTH_IN_PERCENT_OF_PARENT + \'%\',
                        \'margin-left\': (100 - WIDTH_IN_PERCENT_OF_PARENT) / 2 + \'%\',
                    });

                var gd = gd3.node();

                Plotly.newPlot(gd, data, layout);
                
                window.onresize = function() {
                    Plotly.Plots.resize(gd);
                };
            </script>
        ';
    }
}