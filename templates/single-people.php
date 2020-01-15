<?php
/**
 * //Template Name: Artist biography page
 * Description: A Page Template that is full width
 *
 * page-0sb.php
 *
 * PHP Version 5.2
 *
 * @category   Theme
 * @package    OLWPT
 * @subpackage Core
 * @author     Leigh Bicknell <leigh@orangeleaf.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://orangeleaf.com
 */

if(OLOPT_DEBUG)
{
    trigger_error('Loading '.basename(__FILE__), E_USER_NOTICE);
}

global $wrapper_class, $post;
$wrapper_class = 'template-sidebar-none';

get_header();

?>
<h1><?php the_title();?></h1>
<?php
echo '<a href="/art-collection/artists/" class="artists-a-z-link"><span>View all Artists</span></a><br/><br/>';
the_content();

if($_GET['expand_artworks']) {
    echo galleryFeed($post->post_excerpt, 100);
} else {
    echo galleryFeed($post->post_excerpt, 20);
}

get_footer();
