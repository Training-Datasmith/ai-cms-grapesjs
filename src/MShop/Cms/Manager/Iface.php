<?php

declare (strict_types=1);
/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 * @package MShop
 * @subpackage Cms
 */
namespace Aimeos\M_Shop\Cms\Manager;

/**
 * Interface for all cms manager classes.
 *
 * @package MShop
 * @subpackage Cms
 */
interface Iface extends \Aimeos\M_Shop\Common\Manager\Iface, \Aimeos\M_Shop\Common\Manager\Find\Iface, \Aimeos\M_Shop\Common\Manager\Lists_Ref\Iface
{
}