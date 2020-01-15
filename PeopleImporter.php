<?php

class PeopleImporter
{

    /**
     * upload functionality
     *
     * @return \PeopleInsert
     */
    public function upload()
    {
        if(isset($_POST['but_submit'])){
            if($_FILES['file']['name'] != ''){
                $uploadedfile = $_FILES['file'];

                $index = new PeopleInsert();
                $index->createPost($uploadedfile['name']);

                $upload_overrides = array( 'auth_form' => false );
                $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                $url = "";
                if ( $movefile && ! isset( $movefile['error'] ) ) {
                    $url = $movefile['url'];
                    echo "url : ".$url;
                } else {
                    echo $movefile['error'];
                }
            }

        }
        return $index;
    }

    /**
     * WP user-interface form
     *
     * @return string
     */
    public function form()
    {
        $include = $this->upload();
        $form = $include;
        $form.= "<h1>Import People Data</h1>
            <form method='post' action='' name='auth_form' enctype='multipart/form-data'>
                  <p>Choose a XML file from your computer, then click Upload People Data and import.</p>
                  <p><input type='file' name='file' value=''></p>
                  <br/>
                  <p class='submit'>
                    <input type='submit' name='but_submit' value='Upload People Data' class='button button-primary'>
                  </p>
            </form>";

        return $form;
    }
}