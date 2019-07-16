<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 * @package    Asset
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */
namespace Moonshiner\BrigthenBundle\Model\DataObject;

use Pimcore\Model\Element\Tag as PimcoreTag;
use \Pimcore\Model\DataObject\Tag as DataObjectTag;

/**
 * @method \Pimcore\Model\Asset\Dao getDao()
 * @method bool __isBasedOnLatestData()
 */
class Tag extends DataObjectTag
{
    public function save()
    {
        // Is a new Element
        if (!$this->getId()) {
            return parent::save();
        }

        // Pimcore Tag already created for this Tag
        $tagExists = PimcoreTag::getTagsForElement('object', $this->getId());
        if($tagExists ) {
            return parent::save();
        }

        // Create new Pimcore Tag
        $tag = new PimcoreTag();
        $tag->setName($this->getKey())->setParentId(0)->save();
        PimcoreTag::addTagToElement('object', $this->getId(), $tag);
        parent::save();
    }

    public static function getByKey( $key )
    {
        $list = new DataObjectTag\Listing();
        return $list->setCondition('o_key = ?', $key)->load()[0];
    }
}