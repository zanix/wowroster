<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: enUS.php 1126 2007-07-27 05:14:27Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Templates
*/
?>
<div class="header_text sgrayborder"><span style="float:right;">
<a href="<?php echo makelink('util-news-comment_edit&amp;id=' . $data['news_id']) ?>">Edit</a></span>
<?php echo $data['author']; ?> - <?php echo $data['date_format']; ?></div>
<?php echo $data['content']; ?>
<br />
<br />