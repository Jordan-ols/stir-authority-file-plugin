<?php

/*
 * @class
 * authImporter
 *
 * Build application
 */
class authImporter
{
    public function OL_Auth_Display()
    {
        $form = new authAdmin();
        $display = $form->get_tabbed_view();
        echo $display;
    }
}