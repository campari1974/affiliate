<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 19.04.2015
 * Time: 01:11
 */

namespace EcZanox;


class CategoryCollection extends Collection implements \Iterator {

    public function addCollection(array $items = array())
    {
        foreach ($items as $item) {
            $this->addItem(new Category($item));
        }
    }
}