<?php

/*
 * @class
 * PeopleInsert
 *
 * Get people data from specific uploaded location, for each read and write into a post
 */
class PeopleInsert
{

    /*__construct()
     *
     * @global type $__CB
     */
    public function __construct()
    {
        global $__CB;
    }

    /*
     * Retrieve the uploaded person record
     *
     * @param
     * @return
     */
    public function getPeople($file)
    {
        $base = $file;
        $contents = file_get_contents($base);
        $item = simplexml_load_string($contents);
        $record = $item->recordList;

        return $record;
    }

    /*
     * Grab data and insert it into post
     *
     * @param
     * @return
     */
    public function insertPost($title, $content, $priref, $fullname, $ref)
    {
        $insert_atts = array(
            'post_title'=>$title,
            'post_type'=>'people',
            'post_content'=>$content,
            'post_status'=>'publish',
            'post_name'=> $fullname,
            'post_excerpt'=> $ref,
            'guid' => $priref
        );

        $auth_id = get_posts(array('name' => $fullname,'post_status'=>'publish','post_type'=>'people'));
        foreach($auth_id as $id) {
            if($id) {
                $post_id = $id->ID;
                $name = $priref;
            }
        }
        if($name && $post_id) {
            $update_atts = array(
                'ID' => $post_id,
                'post_title'=>$title,
                'post_type'=>'people',
                'post_content'=>$content,
                'post_status'=>'publish',
                'post_name' => $fullname,
                'post_excerpt'=> $ref,
                'guid' => $priref
            );
            $post = wp_update_post($update_atts);
        } else {
            $post = wp_insert_post($insert_atts);
        }

        $post.= $title;
        $post.= $content;
        return $post;
    }

    /*
     * Grab data and create content of post
     *
     * @param
     * @return
     */
    public function createPost($xml)
    {
        $person = $this->getPeople($xml);
        foreach($person->record as $field) {
            $items = json_decode(get_option('mapfields'), true);
            if(!$items) {
                //load default items if custom index fields are empty **ADLIB FORMAT**
                $items = array(
                    'Name'      => 'name',
                    'Creator ID' => 'priref',
                    'Birth Date' => 'birth.date.start',
                    'Death Date' => 'death.date.start',
                    'Biography'  => 'biography',
                );
            }
            $priref = $field->priref;
            $fullname = $field->name;
            $ref = $field->name;

            //build title

            $fn = $field->{'forename'};
            $sn = $field->{'surname'};
            $dob = $field->{'birth.date.precision'};
            $dod = $field->{'death.date.precision'};

            $date = '('.$dob.' - '.$dod.')';

            //if forename/surename field(s) exist
            if(!$dob && !$dob) {
                $date = '';
            }
            if(!$fn || !$sn) {
                $title = $field->{'name'}.' '.$date;
            } else {
                $title = $fn.' '.$sn.' '.$date;
            }

            //build HTML for content
            $content = '<ul class="auth_record">';
            foreach($items as $item=>$val) {
                if($field->{$val}) {

                    $content.= '<li><strong>'.$item.': </strong><span>'.$field->{$val}.'</span></li>';
                }
            }
            $content.= '</ul>';
            $artist = $this->insertPost($title, $content, $priref, $fullname, $ref);
        }

        return $artist;
    }
}

