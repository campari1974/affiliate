<?php
/**
 * Project: affiliate_affilinet
 * (c) 2014 Giacomo Barbalinardo <info@ready24it.eu>
 * Date: 04.12.2014
 * Time: 00:43
 */

namespace Endcore\Result;

use Endcore\Category;


class CategoryList extends SeekableList{

    public function __construct($response)
    {
        $this->_totalResults = $response->GetCategoryListSummary->Records;
        if ($this->_totalResults === 0) {
            throw new \Exception('No Categories found.');
        }

        $this->_results = $response->Categories->Category;
    }

    public function current()
    {
        if(is_array($this->_results))
        return new Category($this->_results[$this->_currentIndex]);
        return $this->_results;
    }

    public function getData(){
        $result = array();
        $result['items'][] = array(
            'id' => '',
            'name' => '-'
        );

        /** @var \Endcore\Category $category */
        if(is_array($this->_results)) {
            foreach ($this->_results as $category_tmp) {
                $category = new Category($category_tmp);
                $current = 'false';

                if (!$category->getCategoryPath()) continue;

                $result['items'][] = array(
                    'id' => $category->getCategoryId(),
                    'name' => $category->getCategoryPath(),
                );
            }
        }
        else
        {
            $category = new Category($this->_results);
            $result['items'][] = array(
                'id' => $category->getCategoryId(),
                'name' => $category->getCategoryPath(),
            );
        }

        usort($result['items'], function ($a, $b) { return strcmp($a['name'], $b['name']); });

        return $result;
    }
} 