<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;

/**
 * ListItem element HTML writer
 *
 * @since 0.10.0
 */
class ListItemRun extends ListItem
{
    /**
     * Write list item
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\ListItemRun) {
            return '';
        }
        $content = '';
        $elementIndex = $this->element->getElementIndex();
        $parent = $this->element->getParent();
        if ($parent && $elementIndex === 1) {
            $content .= '<' . $this->getListFormat() .'>';
        } elseif ($parent && $elementIndex > 1) {
            $previousSibling = $parent->getElement($elementIndex-2);
            $previousSiblingIsListItemRun = $previousSibling instanceof \PhpOffice\PhpWord\Element\ListItemRun;
            if (!$previousSiblingIsListItemRun) {
                $content .= '<' . $this->getListFormat() .'>';
            }
        }
        $writer = new Container($this->parentWriter, $this->element);
        $content .= $writer->write(true) . PHP_EOL;
        if ($parent) {
            $nextSibling = $parent->getElement($elementIndex);
            $nextSiblingIsListItemRun = $nextSibling instanceof \PhpOffice\PhpWord\Element\ListItemRun;
            if (!$nextSibling || !$nextSiblingIsListItemRun) {
                $content .= '</' . $this->getListFormat() .'>';
            }
        }
        return $content;
    }

    protected function getListFormat() {
        $numberStyleIdentifier = $this->element->getStyle()->getNumStyle();
        $itemDepth = $this->element->getDepth();
        $numberStyle = Style::getStyle($numberStyleIdentifier);

        $numberingLevel = $numberStyle->getLevels()[$itemDepth];
        $format = $numberingLevel->getFormat();
        return $format === 'bullet' ? 'ul' : 'ol';
    }

}
