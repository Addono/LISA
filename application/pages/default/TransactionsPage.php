<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class TransactionsPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'transactions-header',
            'intersection',
        ];
    }

    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole($this->ci, Role::ROLE_USER);
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $loginId = getLoggedInLoginId($this->ci->session);

        $transactions['subject'] = $this->ci->User_Transaction->getAllForSubjectId($loginId);
        $transactions['author'] = $this->ci->User_Transaction->getAllForAuthorId($loginId);
        $this->setData('transactions', $transactions);

        $fields = [
            'author' => 'author_name',
            'subject' => 'subject_name',
            'amount' => Consumption::FIELD_AMOUNT,
            'delta' => Transaction::FIELD_DELTA,
            'time' => Transaction::FIELD_TIME,
        ];
        $this->setData('fields', $fields);

        $sumDeltaByWeek = $this->ci->Transaction->getSumDeltaSubjectIdByWeek($loginId);

        $x = [];
        $y = [];

        $weekNr = $sumDeltaByWeek[0]['week'];
        $yearNr = $sumDeltaByWeek[0]['year'];
        foreach ($sumDeltaByWeek as $week) {
            // Reset the week number counter if a new year is started.
            if ($yearNr !== $week['year']) {
                $weekNr = 1;
            }

            // Fill all missing weeks.
            for (; $weekNr < $week['week']; $weekNr++) {
                $x[] = "'" . $weekNr . " - " . $week['year'] . "'";
                $y[] = 0;
            }

            $x[] = "'" . $week['week'] . " - " . $week['year'] . "'";
            $y[] = -$week['sum'];

            $weekNr++;
        }

        $this->addScript(
            "<script>
                var data = [
                    {
                        x: [" . implode(",", $x) . "],
                        y: [" . implode(",", $y) . "],
                        type: 'shatter',
                        line: {shape: 'hvh'}
                    }
                ];
                
                var layout = {
                    xAxis: {
                        min: 0
                    }
                };
                
                var d3 = Plotly.d3;
                var WIDTH_IN_PERCENT_OF_PARENT = 100;
                
                var gd3 = d3.select('#chart')
                    .append('div')
                    .style({
                        width: WIDTH_IN_PERCENT_OF_PARENT + '%',
                        'margin-left': (100 - WIDTH_IN_PERCENT_OF_PARENT) / 2 + '%',
                    });

                var gd = gd3.node();

                Plotly.newPlot(gd, data, layout);
                
                window.onresize = function() {
                    Plotly.Plots.resize(gd);
                };
            </script>"
        );

        $this->addScript(
    "<script>
            $(document).ready(function() {
                $(\"input[type=radio\").change(function() {
                    $(this).parents('ul').children('.active').removeClass('active');
                    $(this).parents('li').addClass('active');
                });
    
                $(\".pagination.horizontal-radio\").click(function() {
                    $(this).find('input[type=radio]').attr('checked', true);
                });
            });
        </script>"
        );
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Role::class,
            User_Transaction::class,
        ];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries(): array
    {
        return [];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers(): array
    {
        return [];
    }
}