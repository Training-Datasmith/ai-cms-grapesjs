<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Cms\Page\Cataloglist;

/**
 * Default implementation for CMS cataloglist.
 *
 * @package Client
 * @subpackage Html
 */
class Standard extends \Aimeos\Client\Html\Catalog\Base implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
    /**
     * Returns the HTML code for insertion into the body.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string HTML code
     */
    public function body(string $uid = ''): string
    {
        return '';
    }
    /**
     * Modifies the cached content to replace content based on sessions or cookies.
     *
     * @param string $content Cached content
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string Modified content
     */
    public function modify(string $content, string $uid): string
    {
        return $this->replace_section($content, $this->view()->csrf()->formfield(), 'catalog.lists.items.csrf');
    }
    /**
     * Sets the necessary parameter values in the view.
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    public function data(\Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null): \Aimeos\Base\View\Iface
    {
        if (!isset($view->page_content)) {
            return parent::data($view, $tags, $expire);
        }
        $texts = [];
        $context = $this->context();
        $config = $context->config();
        $cntl = \Aimeos\Controller\Frontend::create($context, 'product');
        /** client/html/cms/page/template-cataloglist
         * Relative path to the HTML template of the page catalog list client.
         *
         * The template file contains the HTML code and processing instructions
         * to generate the HTML code that is inserted into the HTML page
         * of the rendered page in the frontend. The configuration string is the
         * path to the template file relative to the templates directory (usually
         * in client/html/templates).
         *
         * You can overwrite the template file configuration in extensions and
         * provide alternative templates. These alternative templates should be
         * named like the default one but with the string "standard" replaced by
         * an unique name. You may use the name of your project for this. If
         * you've implemented an alternative client class as well, "standard"
         * should be replaced by the name of the new class.
         *
         * @param string Relative path to the template creating code for the catalog list
         * @since 2021.07
         * @category Developer
         * @see client/html/cms/page/template-body
         * @see client/html/cms/page/template-header
         */
        $template = $config->get('client/html/cms/page/template-cataloglist', 'cms/page/cataloglist/list');
        $domains = $config->get('client/html/catalog/lists/domains', ['media', 'media/property', 'price', 'text']);
        if ($view->config('client/html/cms/page/basket-add', false)) {
            $domains = array_merge_recursive($domains, ['product' => ['default'], 'attribute' => ['variant', 'custom', 'config']]);
        }
        libxml_use_internal_errors(true);
        foreach ($view->page_content as $content) {
            $dom = new \Dom_Document('1.0', 'UTF-8');
            $dom->load_html($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $nodes = $dom->get_elements_by_tag_name('cataloglist');
            while ($nodes->length > 0) {
                $node = $nodes->item(0);
                $catid = $node->has_attribute('catid') ? $node->get_attribute('catid') : null;
                $type = $node->has_attribute('type') ? $node->get_attribute('type') : 'default';
                $limit = $node->has_attribute('limit') ? $node->get_attribute('limit') : 3;
                $products = (clone $cntl)->uses($domains)->category($catid, $type)->slice(0, $limit)->search();
                $articles = $products->get_ref_items('product', 'default', 'default')->flat(1)->union($products);
                $attr_map = $articles->get_ref_items('attribute')->flat(1)->group_by('attribute.type');
                $attr_types = $this->attribute_types($attr_map->keys());
                $this->add_meta_items($products, $expire, $tags);
                $tview = $context->view()->set('products', $products)->set('attributeTypes', $attr_types);
                if (!$products->is_empty() && (bool) $config->get('client/html/catalog/lists/stock/enable', true) === true) {
                    $tview->items_stock_url = $this->get_stock_url($tview, $articles);
                }
                $pdom = new \Dom_Document('1.0', 'UTF-8');
                $pdom->load_html($tview->render($template), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $pnode = $dom->import_node($pdom->document_element, true);
                $node->parent_node->replace_child($pnode, $node);
            }
            $texts[] = $dom->save_html();
        }
        libxml_clear_errors();
        $view->page_content = $texts;
        return parent::data($view, $tags, $expire);
    }
    /**
     * Returns the attribute type items for the given codes
     *
     * @param \Aimeos\Map $codes List of attribute type codes
     * @return \Aimeos\Map List of attribute type items
     */
    protected function attribute_types(\Aimeos\Map $codes): \Aimeos\Map
    {
        $manager = \Aimeos\M_Shop::create($this->context(), 'attribute/type');
        $filter = $manager->filter(true)->add('attribute.type.domain', '==', 'product')->add('attribute.type.code', '==', $codes)->order('attribute.type.position');
        return $manager->search($filter->slice(0, count($codes)));
    }
}