<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */
$enc = $this->encoder();
/** client/html/cms/page/metatags
 * Adds the title, meta and link tags to the HTML header
 *
 * By default, each instance of the cms list component adds some HTML meta
 * tags to the page head section, like page title, meta keywords and description
 * as well as some link tags to support browser navigation. If several instances
 * are placed on one page, this leads to adding several title and meta tags used
 * by search engine. This setting enables you to suppress these tags in the page
 * header and maybe add your own to the page manually.
 *
 * @param boolean True to display the meta tags, false to hide it
 * @since 2021.01
 * @category Developer
 * @category User
 * @see client/html/cms/lists/metatags
 */
if (($path = $this->page_cms_item->get_url()) !== '/') {
    $url = $this->link('client/html/cms/page/url', ['path' => trim($this->page_cms_item->get_url(), '/')], ['absoluteUri' => true]);
} else {
    $url = (string) $this->request()->get_uri();
}
if (isset($this->page_cms_item)) {
    ?>

	<?php 
    if ((bool) $this->config('client/html/cms/page/metatags', true) === true) {
        ?>

		<title><?php 
        echo $enc->html(strip_tags($this->page_cms_item->get_name()));
        ?> | <?php 
        echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
        ?></title>

		<link rel="canonical" href="<?php 
        echo $enc->attr($url);
        ?>">

		<meta property="og:type" content="article">
		<meta property="og:title" content="<?php 
        echo $enc->attr($this->page_cms_item->get_name());
        ?>">
		<meta property="og:url" content="<?php 
        echo $enc->attr($url);
        ?>">

		<?php 
        foreach ($this->page_cms_item->get_ref_items('media', 'default', 'default') as $media_item) {
            ?>
			<meta property="og:image" content="<?php 
            echo $enc->attr($this->content($media_item->get_url()));
            ?>">
		<?php 
        }
        ?>

		<?php 
        foreach ($this->page_cms_item->get_ref_items('text', 'meta-description', 'default') as $text_item) {
            ?>
			<meta property="og:description" content="<?php 
            echo $enc->attr($text_item->get_content());
            ?>">
			<meta name="description" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

		<?php 
        foreach ($this->page_cms_item->get_ref_items('text', 'meta-keyword', 'default') as $text_item) {
            ?>
			<meta name="keywords" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

		<meta name="twitter:card" content="summary_large_image">

	<?php 
    }
    ?>

<?php 
}
?>

<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.css', 'fs-theme', true));
?>">
<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-lists.css', 'fs-theme', true));
?>">
<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/cms-page.css', 'fs-theme', true));
?>">

<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.js', 'fs-theme', true));
?>"></script>
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-lists.js', 'fs-theme', true));
?>"></script>
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/cms-page.js', 'fs-theme', true));
?>"></script>

<?php 
echo $this->get('pageHeader');