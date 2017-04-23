<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class TestPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'test-header',
            'intersection',
        ];
    }

    public function hasAccess(): bool
    {
        return true;
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
        ob_start();
        ?><script>

        $('.buy').click(function () {
            $.ajax({
                url: "<?=site_url($this->data['group'] . '/ApiBuy')?>",
                data: {
                    id: 23
                },
                type: "POST",
                dataType: "json"
            })
                .done(function (json) {
                    console.log(json);
                })
                .fail(function (xhr, status, errorMessage) {
                    alert(errorMessage);
                });
        });

    </script><?php
        $this->addScript(ob_get_contents());
        ob_clean();
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