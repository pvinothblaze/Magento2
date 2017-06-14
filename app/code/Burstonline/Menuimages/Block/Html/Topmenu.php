<?php

namespace Burstonline\Menuimages\Block\Html;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    protected function _getHtml(
    \Magento\Framework\Data\Tree\Node $menuTree,
    $childrenWrapClass,
    $limit,
    $colBrakes = []
) {
    $html = '';

    $children = $menuTree->getChildren();
    $parentLevel = $menuTree->getLevel();
    $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

    $counter = 1;
    $itemPosition = 1;
    $childrenCount = $children->count();

    $parentPositionClass = $menuTree->getPositionClass();
    $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

    foreach ($children as $child) {
        $child->setLevel($childLevel);
        $child->setIsFirst($counter == 1);
        $child->setIsLast($counter == $childrenCount);
        $child->setPositionClass($itemPositionClassPrefix . $counter);

        $outermostClassCode = '';
        $outermostClass = $menuTree->getOutermostClass();

        if ($childLevel == 0 && $outermostClass) {
            $outermostClassCode = ' class="' . $outermostClass . '" ';
            $child->setClass($outermostClass);
        }

        if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
            $html .= '</ul></li><li class="column"><ul>';
        }

        $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
        $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                $child->getName()
            ) . '</span></a>' ;
        // Use category image instead of name if available
        if($childLevel!=0){
			$html .= $child->getDataByKey('image_url') ? '<img src="' . $child->getDataByKey('image_url') . '">' : $childLevel;
		}
        $html .= $this->_addSubMenu(
            $child,
            $childLevel,
            $childrenWrapClass,
            $limit
        ) . '</li>';
        $itemPosition++;
        $counter++;
    }

    if (count($colBrakes) && $limit) {
        $html = '<li class="column"><ul>' . $html . '</ul></li>';
    }

    return $html;
}


}
