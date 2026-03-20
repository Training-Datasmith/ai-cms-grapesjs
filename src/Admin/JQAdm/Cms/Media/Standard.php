<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 * @package Admin
 * @subpackage JQAdm
 */
namespace Aimeos\Admin\Jq_Adm\Cms\Media;

sprintf('media');
// for translation
/**
 * Default implementation of cms media JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard extends \Aimeos\Admin\Jq_Adm\Common\Admin\Factory\Base implements \Aimeos\Admin\Jq_Adm\Common\Admin\Factory\Iface
{
    /** admin/jqadm/cms/media/name
     * Name of the media subpart used by the JQAdm cms implementation
     *
     * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Cms\Media\Myname".
     * The name is case-sensitive and you should avoid camel case names like "MyName".
     *
     * @param string Last part of the JQAdm class name
     * @since 2021.04
     * @category Developer
     */
    /**
     * Adds the required data used in the cms template
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface View object with assigned parameters
     */
    public function data(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $context = $this->context();
        $type_manager = \Aimeos\M_Shop::create($context, 'media/type');
        $list_type_manager = \Aimeos\M_Shop::create($context, 'cms/lists/type');
        $search = $type_manager->filter(true)->order('media.type.code')->slice(0, 10000);
        $list_search = $list_type_manager->filter(true)->order('cms.lists.type.code')->slice(0, 10000);
        $view->media_list_types = $list_type_manager->search($list_search);
        $view->media_types = $type_manager->search($search);
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
        $view->media_data = $this->to_array($view->item, true);
        $view->media_body = parent::copy();
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
        $item_data = $this->to_array($view->item);
        $data = array_replace_recursive($item_data, $view->param('media', []));
        foreach ($data as $idx => $entry) {
            $data[$idx]['media.siteid'] = $siteid;
            $data[$idx]['media.url'] = $entry['media.url'] ?? null;
            $data[$idx]['media.preview'] = $entry['media.preview'] ?? null;
            $data[$idx]['cms.lists.siteid'] = $siteid;
        }
        $view->media_data = $data;
        $view->media_body = parent::create();
        return $this->render($view);
    }
    /**
     * Deletes a resource
     *
     * @return string|null HTML output
     */
    public function delete(): ?string
    {
        parent::delete();
        $item = $this->view()->item;
        $this->delete_media_items($item, $item->get_list_items('media', null, null, false)->to_array());
        return null;
    }
    /**
     * Returns a single resource
     *
     * @return string|null HTML output
     */
    public function get(): ?string
    {
        $view = $this->object()->data($this->view());
        $view->media_data = $this->to_array($view->item);
        $view->media_body = parent::get();
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
        $view->media_body = parent::save();
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
        /** admin/jqadm/cms/media/decorators/excludes
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
         *  admin/jqadm/cms/media/decorators/excludes = array( 'decorator1' )
         *
         * This would remove the decorator named "decorator1" from the list of
         * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
         * "admin/jqadm/common/decorators/default" to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/decorators/global
         * @see admin/jqadm/cms/media/decorators/local
         */
        /** admin/jqadm/cms/media/decorators/global
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
         *  admin/jqadm/cms/media/decorators/global = array( 'decorator1' )
         *
         * This would add the decorator named "decorator1" defined by
         * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/decorators/excludes
         * @see admin/jqadm/cms/media/decorators/local
         */
        /** admin/jqadm/cms/media/decorators/local
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
         *  admin/jqadm/cms/media/decorators/local = array( 'decorator2' )
         *
         * This would add the decorator named "decorator2" defined by
         * "\Aimeos\Admin\JQAdm\Cms\Decorator\Decorator2" only to the JQAdm client.
         *
         * @param array List of decorator names
         * @since 2021.04
         * @category Developer
         * @see admin/jqadm/common/decorators/default
         * @see admin/jqadm/cms/media/decorators/excludes
         * @see admin/jqadm/cms/media/decorators/global
         */
        return $this->create_sub_client('cms/media/' . $type, $name);
    }
    /**
     * Removes the media reference and the media item if not shared
     *
     * @param \Aimeos\MShop\Cms\Item\Iface $item Cms item including media reference
     * @param array $listItems Media list items to be removed
     * @return \Aimeos\MShop\Cms\Item\Iface Modified cms item
     */
    protected function delete_media_items(\Aimeos\M_Shop\Cms\Item\Iface $item, array $list_items): \Aimeos\M_Shop\Cms\Item\Iface
    {
        $context = $this->context();
        $media_manager = \Aimeos\M_Shop::create($context, 'media');
        $manager = \Aimeos\M_Shop::create($context, 'cms');
        $search = $manager->filter();
        foreach ($list_items as $list_item) {
            $func = $search->make('cms:has', ['media', $list_item->get_type(), $list_item->get_ref_id()]);
            $search->set_conditions($search->compare('!=', $func, null));
            $items = $manager->search($search);
            $ref_item = null;
            if (count($items) === 1 && ($ref_item = $list_item->get_ref_item()) !== null) {
                $media_manager->delete($ref_item);
            }
            $item->delete_list_item('media', $list_item, $ref_item);
        }
        return $item;
    }
    /**
     * Returns the list of sub-client names configured for the client.
     *
     * @return array List of JQAdm client names
     */
    protected function get_sub_client_names(): array
    {
        /** admin/jqadm/cms/media/subparts
         * List of JQAdm sub-clients rendered within the cms media section
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
        return $this->context()->config()->get('admin/jqadm/cms/media/subparts', []);
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
        $context = $this->context();
        $manager = \Aimeos\M_Shop::create($context, 'cms');
        $media_manager = \Aimeos\M_Shop::create($context, 'media');
        $list_items = $item->get_list_items('media', null, null, false);
        $files = (array) $this->view()->request()->get_uploaded_files();
        foreach ($data as $idx => $entry) {
            $id = $this->val($entry, 'media.id', '');
            $type = $this->val($entry, 'cms.lists.type', 'default');
            $list_item = $item->get_list_item('media', $type, $id, false) ?: $manager->create_list_item();
            $ref_item = $list_item->get_ref_item() ?: $media_manager->create();
            $ref_item->from_array($entry, true)->set_domain('cms');
            $preview = $this->val($files, 'media/' . $idx . '/preview');
            $file = $this->val($files, 'media/' . $idx . '/file');
            if ($ref_item->get_id() === null && $ref_item->get_url() !== '') {
                $ref_item = $media_manager->copy($ref_item);
            }
            $ref_item = $media_manager->upload($ref_item, $file, $preview);
            $list_item->from_array($entry, true)->set_position($idx)->set_config([]);
            foreach ((array) $this->val($entry, 'config', []) as $cfg) {
                if (($key = trim($cfg['key'] ?? '')) !== '' && ($val = trim($cfg['val'] ?? '')) !== '') {
                    $list_item->set_config_value($key, json_decode($val, true) ?? $val);
                }
            }
            $item->add_list_item('media', $list_item, $ref_item);
            unset($list_items[$list_item->get_id()]);
        }
        return $this->delete_media_items($item, $list_items->to_array());
    }
    /**
     * Constructs the data array for the view from the given item
     *
     * @param \Aimeos\MShop\Cms\Item\Iface $item Cms item object including referenced domain items
     * @param bool $copy True if items should be copied, false if not
     * @return string[] Multi-dimensional associative list of item data
     */
    protected function to_array(\Aimeos\M_Shop\Cms\Item\Iface $item, bool $copy = false): array
    {
        $data = [];
        $site_id = $this->context()->locale()->get_site_id();
        foreach ($item->get_list_items('media', null, null, false) as $list_item) {
            if (($ref_item = $list_item->get_ref_item()) === null) {
                continue;
            }
            $list = $list_item->to_array(true) + $ref_item->to_array(true);
            if ($copy === true) {
                $list['cms.lists.siteid'] = $site_id;
                $list['cms.lists.id'] = '';
                $list['media.siteid'] = $site_id;
                $list['media.id'] = null;
            }
            $list['media.previews'] = $this->view()->imageset($ref_item->get_previews(), $ref_item->get_file_system());
            $list['media.preview'] = $this->view()->content($ref_item->get_preview(), $ref_item->get_file_system());
            $list['cms.lists.datestart'] = str_replace(' ', 'T', $list['cms.lists.datestart'] ?? '');
            $list['cms.lists.dateend'] = str_replace(' ', 'T', $list['cms.lists.dateend'] ?? '');
            $list['config'] = [];
            foreach ($list_item->get_config() as $key => $value) {
                $list['config'][] = ['key' => $key, 'val' => $value];
            }
            $data[] = $list;
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
        /** admin/jqadm/cms/media/template-item
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
        $tplconf = 'admin/jqadm/cms/media/template-item';
        $default = 'cms/item-media';
        return $view->render($view->config($tplconf, $default));
    }
}