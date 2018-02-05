<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class LeaderboardPage extends PageFrame
{

    const LEADERBOARD_SIZE = 10;

    public function getViews(): array
    {
        return [
            'leaderboard-header',
            'intersection',
        ];
    }

    public function hasAccess(): bool
    {
        if (! isLoggedInAndHasRole($this->ci, Role::ROLE_USER)) {
            return false;
        }

        $loginId = getLoggedInLoginId($this->ci->session);
        $isOnTheLeaderboard = $this->ci->Transaction->getSumDeltaSubjectIdWithinTop($loginId, false, self::LEADERBOARD_SIZE);

        return  $isOnTheLeaderboard;
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
        $leaderboard = $this->ci->User_Transaction->getSumDeltaForAllSubjectId(false, self::LEADERBOARD_SIZE);

        $this->setData('entries', $leaderboard);

        $fields = [
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'sum' => 'sum',
        ];
        $this->setData('fields', $fields);

        $sumByWeek = $this->ci->Transaction->getSumDeltaByWeek();

        $x = [];
        $y = [];

        $weekNr = $sumByWeek[0]['week'];
        $yearNr = $sumByWeek[0]['year'];
        foreach ($sumByWeek as $week) {
            // Reset the week number counter if a new year is started.
            if ($yearNr !== $week['year']) {
                // Fill all missing weeks until the start of the year.
                while(++$weekNr % 52 !== 1) {
                    $x[] = "'" . $weekNr . " - " . $week['year'] . "'";
                    $y[] = 0;
                }

                $weekNr = 1;
                $yearNr = $week['year'];
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

        $this->addScript("
            <script src=\"" . base_url('node_modules/plotly.js/dist/plotly.min.js') . "\" type=\"text/javascript\"></script>
            <script>
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