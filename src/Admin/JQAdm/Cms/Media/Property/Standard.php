<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 * @package Admin
 * @subpackage JQAdm
 */
namespace Aimeos\Admin\Jq_Adm\Cms\Media\Property;

sprintf('property');
// for translation
/**
 * Default implementation of cms media JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard extends \Aimeos\Admin\Jq_Adm\Common\Admin\Factory\Base implements \Aimeos\Admin\Jq_Adm\Common\Admin\Factory\Iface
{
    /** admin/jqadm/cms/media/property/name
     * Name of the property subpart used by the JQAdm cms media implementation
     *
     * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Cms\Media\Property\Myname".
     * The name is case-sensitive and you should avoid camel case names like "MyName".
     *
     * @param string Last part of the JQAdm class name
     * @since 2021.04
     * @category Developer
     */
    /**
     * Adds the required data used in the template
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface View object with assigned parameters
     */
    public function data(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $manager = \Aimeos\M_Shop::create($this->context(), 'media/property/type');
        $search = $manager->filter(true)->slice(0, 10000);
        $search->set_conditions($search->compare('==', 'media.property.type.domain', 'media'));
        $search->set_sortations([$search->sort('+', 'media.property.type.position')]);
        $view->property_types = $manager->search($search);
        return $view;
    }
    /**
     * Copies a resource
     *
     * @return string|null HTML output
     */
    public function copy(): ?string
    {
        $view = $this->object()->data($this->view());
        $view->media_data = $this->to_array($view->item, $view->get('mediaData', []), true);
        $view->property_body = parent::copy();
        return $this->render($view);
    }
    /**
     * Creates a new resource
     *
     * @return string|null HTML output
     */
    public function create(): ?string
    {
        $view = $this->object()->data($this->view());
        $siteid = $this->context()->locale()->get_site_id();
        $data = $view->get('mediaData', []);
        foreach ($data as $index => $entry) {
            foreach ($view->value($entry, 'property', []) as $idx => $y) {
                $data[$index]['property'][$idx]['media.property.siteid'] = $siteid;
            }
        }
        $view->property_data = $data;
        $view->property_body = parent::create();
        return $this->render($view);
    }
    /**
     * Returns a single resource
     *
     * @return string|null HTML output
     */
    public function get(): ?string
    {
        $view = $this->object()->data($this->view());
        $view->media_data = $this->to_array($view->item, $view->get('mediaData', []));
        $view->property_body = parent::get();
        return $this->render($view);
    }
    /**
     * Saves the data
     *
     * @return string|null HTML output
     */
    public function save(): ?string
    {
        $view = $this->view();
        $view->item = $this->from_array($view->item, $view->param('media', []));
        $view->property_body = parent::save();
        return null;
    }
    /**
     * Returns the sub-client given by its name.
     *
     * @param string $type Name of the client type
     * @param string|null $name Name of the sub-client (Default if null)
     * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
     */
    public function get_sub_client(string $type, ?string $name = null): \Aimeos\Admin\Jq_Adm\Iface
    {
        /** admin/jqadm/cms/media/property/decorators/excludes
         * Excludes decorators added by the "common" option from the cms JQAdm client
         *
         * Decorators extend the functionality of a class by adding new aspects
         * (e.g. log what is currently done), executing the methods of the underlying
         * class only in certain conditions (e.g. only for logged in users) or
         * modify what is returned to the caller.
         *
         * This option allows you to remove a decorator added via
         * "admin/jqadm/common/decorators/default" before they are wrapped
         * around the JQAdm client.
         *
         *  admin/jqadm/cms/media/property/decorators/excludes = array( 'decorator1' )
         *
         * This would remove the decorator named "decorator1" from the list of
         * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
         * "admin/jqadm/common/decorators/default" to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/property/decorators/global
         * @see admin/jqadm/cms/media/property/decorators/local
         */
        /** admin/jqadm/cms/media/property/decorators/global
         * Adds a list of globally available decorators only to the cms JQAdm client
         *
         * Decorators extend the functionality of a class by adding new aspects
         * (e.g. log what is currently done), executing the methods of the underlying
         * class only in certain conditions (e.g. only for logged in users) or
         * modify what is returned to the caller.
         *
         * This option allows you to wrap global decorators
         * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
         *
         *  admin/jqadm/cms/media/property/decorators/global = array( 'decorator1' )
         *
         * This would add the decorator named "decorator1" defined by
         * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/property/decorators/excludes
         * @see admin/jqadm/cms/media/property/decorators/local
         */
        /** admin/jqadm/cms/media/property/decorators/local
         * Adds a list of local decorators only to the cms JQAdm client
         *
         * Decorators extend the functionality of a class by adding new aspects
         * (e.g. log what is currently done), executing the methods of the underlying
         * class only in certain conditions (e.g. only for logged in users) or
         * modify what is returned to the caller.
         *
         * This option allows you to wrap local decorators
         * ("\Aimeos\Admin\JQAdm\Cms\Decorator\*") around the JQAdm client.
         *
         *  admin/jqadm/cms/media/property/decorators/local = array( 'decorator2' )
         *
         * This would add the decorator named "decorator2" defined by
         * "\Aimeos\Admin\JQAdm\Cms\Decorator\Decorator2" only to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/property/decorators/excludes
         * @see admin/jqadm/cms/media/property/decorators/global
         */
        return $this->create_sub_client('cms/media/property/' . $type, $name);
    }
    /**
     * Returns the list of sub-client names configured for the client.
     *
     * @return array List of JQAdm client names
     */
    protected function get_sub_client_names(): array
    {
        /** admin/jqadm/cms/media/property/subparts
         * List of JQAdm sub-clients rendered within the cms media property section
         *
         * The output of the frontend is composed of the code generated by the JQAdm
         * clients. Each JQAdm client can consist of serveral (or none) sub-clients
         * that are responsible for rendering certain sub-parts of the output. The
         * sub-clients can contain JQAdm clients themselves and therefore a
         * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
         * the output that is placed inside the container of its parent.
         *
         * At first, always the JQAdm code generated by the parent is printed, then
         * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
         * determines the order of the output of these sub-clients inside the parent
         * container. If the configured list of clients is
         *
         *  array( "subclient1", "subclient2" )
         *
         * you can easily change the order of the output by reordering the subparts:
         *
         *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
         *
         * You can also remove one or more parts if they shouldn't be rendered:
         *
         *  admin/jqadm/<clients>/subparts = array( "subclient1" )
         *
         * As the clients only generates structural JQAdm, the layout defined via CSS
         * should support adding, removing or reordering content by a fluid like
         * design.
         *
         * @param array List of sub-client names
         * @since 2021.04
         * @category Developer
         */
        return $this->context()->config()->get('admin/jqadm/cms/media/property/subparts', []);
    }
    /**
     * Creates new and updates existing items using the data array
     *
     * @param \Aimeos\MShop\Cms\Item\Iface $item Cms item object without referenced domain items
     * @param array $data Data array
     * @return \Aimeos\MShop\Cms\Item\Iface Modified cms item
     */
    protected function from_array(\Aimeos\M_Shop\Cms\Item\Iface $item, array $data): \Aimeos\M_Shop\Cms\Item\Iface
    {
        $manager = \Aimeos\M_Shop::create($this->context(), 'media');
        $index = 0;
        foreach ($item->get_ref_items('media', null, null, false) as $ref_item) {
            $prop_items = $ref_item->get_property_items(null, false);
            foreach ((array) $this->val($data, $index . '/property', []) as $entry) {
                if (isset($prop_items[$entry['media.property.id']])) {
                    $prop_item = $prop_items[$entry['media.property.id']];
                    unset($prop_items[$entry['media.property.id']]);
                } else {
                    $prop_item = $manager->create_property_item();
                }
                $prop_item->from_array($entry, true);
                $ref_item->add_property_item($prop_item);
            }
            foreach ($prop_items as $prop_item) {
                // Don't delete preview image URLs
                if (!ctype_digit($prop_item->get_type())) {
                    $ref_item->delete_property_item($prop_item);
                }
            }
            $index++;
        }
        return $item;
    }
    /**
     * Constructs the data array for the view from the given item
     *
     * @param \Aimeos\MShop\Cms\Item\Iface $item Cms item object including referenced domain items
     * @param array $data Associative list of media data
     * @param bool $copy True if items should be copied, false if not
     * @return string[] Multi-dimensional associative list of item data
     */
    protected function to_array(\Aimeos\M_Shop\Cms\Item\Iface $item, array $data, bool $copy = false): array
    {
        $idx = 0;
        $site_id = $this->context()->locale()->get_site_id();
        foreach ($item->get_ref_items('media', null, null, false) as $media_item) {
            $data[$idx]['property'] = [];
            foreach ($media_item->get_property_items(null, false) as $prop_item) {
                $list = $prop_item->to_array(true);
                if ($copy === true) {
                    $list['media.property.siteid'] = $site_id;
                    $list['media.property.id'] = '';
                }
                $data[$idx]['property'][] = $list;
            }
            $idx++;
        }
        return $data;
    }
    /**
     * Returns the rendered template including the view data
     *
     * @param \Aimeos\Base\View\Iface $view View object with data assigned
     * @return string HTML output
     */
    protected function render(\Aimeos\Base\View\Iface $view): string
    {
        /** admin/jqadm/cms/media/property/template-item
         * Relative path to the HTML body template of the media subpart for cmss.
         *
         * The template file contains the HTML code and processing instructions
         * to generate the result shown in the body of the frontend. The
         * configuration string is the path to the template file relative
         * to the templates directory (usually in admin/jqadm/templates).
         *
         * You can overwrite the template file configuration in extensions and
         * provide alternative templates. These alternative templates should be
         * named like the default one but with the string "default" replaced by
         * an unique name. You may use the name of your project for this. If
         * you've implemented an alternative client class as well, "default"
         * should be replaced by the name of the new class.
         *
         * @param string Relative path to the template creating the HTML code
         * @since 2021.04
         * @category Developer
         */
        $tplconf = 'admin/jqadm/cms/media/property/template-item';
        $default = 'cms/item-media-property';
        return $view->render($view->config($tplconf, $default));
    }
}