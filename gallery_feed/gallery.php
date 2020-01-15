<?php
/**
 * gallery.php
 *
 * PHP Version 5.6
 *
 * @category   Theme
 * @package    OLWPT
 * @subpackage Core
 * @author     Jordan Quinn <jordan@orangeleaf.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://orangeleaf.com
 */


/*
 * Foreach person generate a custom gallery of related artwork
 *
 * @param $name, $count
 * @return $content
 */
function galleryFeed($name, $count)
{
    $content = '';
    $p = 0;
    if($count) {
        $num = $count;
    }

    $dc_creator = 'dc.creator:(("'.rawurlencode($name).'"))';

    // Hack on Maclean, Will record.
    if ($name === 'Maclean, Will') {
        $dc_creator = 'dc.creator:"Maclean"+dc.creator:"Will"-dc.creator:"Diane"';
    }

    $url = file_get_contents(cb_get_option('feedurl').'/os/?q.op=AND&queryType=lucene&startPage=0&q=*:*&count='.$num
            . '&fq[]=partner_code:"STIAC"&fq[]='.$dc_creator.'&fq[]=have_thumbnail:1&fq[]'
            . '=dcterms.isPartOf:STIR');

    $feed = simplexml_load_string($url);

    $content.= '<div class="grids boxes equal">';

    if (isset($_GET['dbg'])) {
        var_dump(cb_get_option('feedurl').'/os/?q.op=AND&queryType=lucene&startPage=0&q=*:*&count='.$num
            . '&fq[]=partner_code:"STIAC"&fq[]=dc.creator:(("'.rawurlencode($name).'"))&fq[]=have_thumbnail:1&fq[]'
            . '=dcterms.isPartOf:STIR');
    }


    foreach($feed->channel->item as $item) {
        $src = str_replace('/75_','/',$item->image->url);
        if($src) {
            $count = $i++;
            $ixif = cb_get_option('components', 'getrecord', 'player_slug').'STIAC/'.$item->guid;
            $img.= '<div class="artwork__item">
                        <div class="box default">
                        <a href="'.$item->link[0].'" title="View Artwork">
                            <img class="related_artwork" src="'.str_replace('JPG','jpg',$src).'" alt="'.$item->title.'"/>
                        </a>
                        </div>
                </div>';
                if(($count + 1) % 5 == 0) {
                    $img.= '</div><div class="grids boxes equal">';
                }
        }
    }
    $aw_count = ($count + 1);
    if($img) {
        $content.= '<h3>Related Artworks for this Artist ( '.$aw_count.' )</h3>';
        if($count >= 19 && $count < 21) {
            echo '<a class="button more-artworks" href="?expand_artworks=1">View More Artworks</a>';
        }
        $content.= $img;
    }
    $content.= '</div></div>';

    echo $content;
}

/**
 * Return Artist biography href dependant on spectrum value of name field (defined + called in getrecord)
 *
 * @param type $name
 * @return boolean
 */
function peopleRecordConnector($name)
{
    $args = array(
        'post_name' => sanitize_title($name),
        'post_type' => 'people'
    );
    $person = get_posts($args);
    if(!$person) {
        return false;
    } else {
        return '/people/'.sanitize_title($name).'/';
    }
}