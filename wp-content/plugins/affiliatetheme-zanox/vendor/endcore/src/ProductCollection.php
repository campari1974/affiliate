<?php
/**
 * Created by affiliatetheme-zanox.
 * User: Giacomo
 * Date: 18.04.2015
 * Time: 16:10
 */

namespace EcZanox;


class ProductCollection extends Collection implements \Iterator{

    public function addCollection(array $items = array()){
        foreach ($items as $item) {
            $this->addItem(new Product($item));
        }
    }
}