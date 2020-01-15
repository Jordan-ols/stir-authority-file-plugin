<?php

/*
 * @class
 * PeopleListing
 *
 * Get people data from imported posts and create a A-Z listing via a shortcode
 */
add_shortcode('stirling_artists_listing', 'authShortcode');

/*
* Generate posts and grab files and permalinks
*/
function authShortcode($atts)
{
    $artists = get_posts([
        'post_type' => 'people',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'name',
        'order'    => 'ASC'
    ]);
    $a = shortcode_atts( array(
        // No options
    ), $atts );

    $groups = range("a","z");

    echo '<ul class="letters-filter-links">';
    foreach($groups as $index) {
        echo '<li class="countries-letters__'.$index.'">';
        echo '<a title="" href="#'.$index.'" data-letter="'.$index.'">'.$index.'</a>';
        echo '</li>';
    }
    echo '</ul>';
    foreach($groups as $group) {
        $cat = '<ul class="artists-az" id="'.$group.'"><li><h2>'.$group.'</h2><ul>';
        foreach($artists as $artist) {
            $title = $artist->post_title;
            $first = $artist->post_name;
            $a = '/people/'.$artist->post_name.'/';
            $item = '<li><a href="'.$a.'">'.$title.'</a></li>';
            if($group === $first[0]) {
                $cat.= $item;
            }
        }
        $cat.= '</ul></li></ul>';

        echo $cat;
    }
}

