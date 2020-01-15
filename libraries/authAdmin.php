<?php

/*
 * @class
 * authListTable
 *
 * Get specific display fields on import data
 */
class authAdmin
{

    /*
     * Admin tabs display
     *
     * @param
     * @return
     */
    public function auth_tab_display($current)
    {
        $tabs = array('upload' => 'Upload', 'index' => 'Settings');
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            $test = "<a class='nav-tab$class' href='?page=artists&tab=$tab'>$name</a>";
            echo $test;
        }
    }

    /*
     * Core importing code for processing and reading of file
     *
     * @param
     * @return
     */
    public function upload()
    {
        if(isset($_POST['but_submit'])){
            if($_FILES['file']['name'] != ''){
                $uploadedfile = $_FILES['file'];
                $upload_overrides = array('test_form' => false, 'mimes' => array('xml' => 'application/xml'));
                $file = $_FILES['file']['tmp_name'];
                $url = "";
                if (isset($file)) {
                    $insert = new PeopleInsert();
                    $insert->createPost($file);
                    echo '<div class="components-notice is-success is-dismissible">
                        <div class="components-notice__content">
                        Your Authority file has successfully imported into the database. You can find the record under
                        <a class="components-button components-notice__action is-link" href="/wp-admin/edit.php?post_type=people">Authority Files</a>.
                        </div>
                    </div>';

                } else {
                    echo "Sorry, there was a problem with your file upload. Please make sure it is in XML format";
                }
            }
        }
        return $index;
    }

    /*
     * Form to add and import XML file
     *
     * @param
     * @return
     */
    public function form()
    {
        $include = $this->upload();
        $form = $include;
        $form.= '
            <div><form method="post" name="auth_upload" enctype="multipart/form-data">
                  <p>Choose a XML file from your computer, then click Upload People Data and import.</p>
                  <p><input type="file" name="file" value=""></p>
                  <br/>
                  <p class="submit">
                    <input type="submit" name="but_submit" value="Import People Data" class="button button-primary">
                  </p>
            </form>
            <img class="auth" src="/wp-content/plugins/stir-auth-importer/loading.gif" alt="loading gif"
            height="50" width="50" style="display:none"/>
            <script>
            $ = jQuery;
            $("form").submit(function() {
                $("img.auth").show();
            });
            </script></div>
            ';


        return $form;
    }

    /*
     * Index fields form from single text area containing json array
     *
     * @param
     * @return
     */
    public function index_fields_form()
    {
        $form.= '<div><form method="post" action="options.php">'
                .wp_nonce_field('update-options').'
                <p><strong>Map Fields:</strong><br />
                    <div><span class="description">Add an array of <code>fields</code> into the box below. These will
                    be the import field maps and provides a way of customising which fields to index per import.<br/>
                    Adlib Example: <code>{
                        "Name" : "name",
                        "ID" : "priref",
                        "Description"  : "description"
                    }</code><br/>
                    Blank the field to load the default fields for mapping.
                    </span></div>
                    <textarea name="mapfields" rows="20" cols="50"/>'.get_option('mapfields').'</textarea>
                </p>
                <p><strong>Art a-z page path:</strong><br />
                    <input type="text" name="artpath" rows="20" cols="50" value="'.get_option('artpath').'"/>
                </p>
                <p>
                    <input type="submit" class="button button-primary" name="Submit" value="Update Settings" /></p>
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="page_options" value="mapfields" />
            </form></div>';

        return $form;
    }

    /**
     * Return tabbed view
     *
     * @param
     * @return
     */
    public function get_tabbed_view()
    {
        echo '<h1>Import People Data</h1>';
        echo '<h2 class="nav-tab-wrapper">';
        $tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'upload';
        $this->auth_tab_display($tab);
        echo '</h2>';

        if ( $tab == 'upload' ) {
            echo $this->form();
        }
        else {
            echo $this->index_fields_form();
        }
    }
}