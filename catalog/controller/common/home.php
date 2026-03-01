<?php

class ControllerCommonHome extends Controller {

  public function index() {

    $this->document->setTitle($this->config->get('config_meta_title'));

    $this->document->setDescription($this->config->get('config_meta_description'));

    $this->document->setKeywords($this->config->get('config_meta_keyword'));



    if (isset($this->request->get['route'])) {

      $this->document->addLink($this->config->get('config_url'), 'canonical');

    }



    $this->load->model('catalog/category');

    $this->load->model('catalog/product');

    $this->load->model('journal3/product');



    $data['categories'] = [];



    $categories = $this->model_catalog_category->getCategories(0);



    foreach ($categories as $category) {

      $filter_data = [

        'filter_category_id' => $category['category_id'],

        'filter_sub_category' => true,

        'start' => 0,

        'limit' => 8, // Limit to latest 6 products

        'sort'  => 'p.date_added',

        'order' => 'DESC'

      ];



      $results = $this->model_catalog_product->getProducts($filter_data);

      $products = [];



      foreach ($results as $result) {

        $products[] = $this->model_journal3_product->formatProduct($result);

      }

      

    //   echo "<pre>";print_r($products);exit();    

        

      if ($products) {

        $data['categories'][] = [

          'name'     => $category['name'],

          'category_id' => $category['category_id'],

          'products' => $products

        ];

      }

    }



    // Load standard layout parts

    $data['column_left']    = $this->load->controller('common/column_left');

    $data['column_right']   = $this->load->controller('common/column_right');

    $data['content_top']    = $this->load->controller('common/content_top');

    $data['content_bottom'] = $this->load->controller('common/content_bottom');

    $data['footer']         = $this->load->controller('common/footer');

    $data['header']         = $this->load->controller('common/header');



    $this->response->setOutput($this->load->view('common/home', $data));

  }

}

