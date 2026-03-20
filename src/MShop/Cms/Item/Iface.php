<?php

declare (strict_types=1);
/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 * @package MShop
 * @subpackage Cms
 */
namespace Aimeos\M_Shop\Cms\Item;

/**
 * Generic interface for cms pages created and saved by cms managers.
 *
 * @package MShop
 * @subpackage Cms
 */
interface Iface extends \Aimeos\M_Shop\Common\Item\Iface, \Aimeos\M_Shop\Common\Item\Lists_Ref\Iface, \Aimeos\M_Shop\Common\Item\Status\Iface
{
    /**
     * Returns the URL of the cms page.
     *
     * @return string URL of the cms page
     */
    public function get_url(): string;
    /**
     * Sets the URL of the cms page.
     *
     * @param string $value URL of the cms page
     * @return \Aimeos\MShop\Cms\Item\Iface Cms page for chaining method calls
     */
    public function set_url(string $value): \Aimeos\M_Shop\Cms\Item\Iface;
    /**
     * Returns the name of the attribute page.
     *
     * @return string Label of the attribute page
     */
    public function get_label(): string;
    /**
     * Sets the new label of the attribute page.
     *
     * @param string $label Type label of the attribute page
     * @return \Aimeos\MShop\Cms\Item\Iface Cms page for chaining method calls
     */
    public function set_label(?string $label): \Aimeos\M_Shop\Cms\Item\Iface;
}