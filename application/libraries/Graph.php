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

        $keyedTransactionData = [];
        foreach ($transactionData as $row) {
            ['year' => $year, 'week' => $week] = $row;
            $keyedTransactionData[$year * 52 + $week - 1] = $row;
        }

        asort($keyedTransactionData);

        reset($keyedTransactionData);
        $lowestKey = key($keyedTransactionData);

        $date = new DateTime('now');
        $largestKey = $date->format('W') + $date->format('Y') * 52;

        for ($i = $lowestKey - $lowestKey % 52, $max = $largestKey; $i < $max; $i++) {
            $week = ($i % 52) + 1;
            $year = (int) floor($i / 52);
            $x[$year][] = $week;
            if (array_key_exists($i, $keyedTransactionData)) {
                $y[$year][] = -$keyedTransactionData[$i]['sum'];
            } else {
                $y[$year][] = 0;
            }
        }

        $data = '[';
        foreach ($y as $year => $_) {
            $data .=
                '{
                    x: [' . implode(',', $x[$year]) . '],
                    y: [' . implode(',', $y[$year]) . '],
                    mode: \'lines\',
                    name: \'' . $year . '\',
                },';
        }
        $data .= ']';

        return '<script>
                var data = ' . $data . ';
                
                var layout = {
                    xaxis: {
                        min: 1,
                        max: 52,
                        title: "Week numer",
                    },
                    yaxis: {
                        min: 0,
                        title: "# Consumptions",
                    },
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