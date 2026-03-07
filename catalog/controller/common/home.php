<?php

class ControllerCommonHome extends Controller
{

  public function index()
  {

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

      // Fetch subcategories
      $subcategories = $this->model_catalog_category->getCategories($category['category_id']);
      $category_ids = [$category['category_id']];
      foreach ($subcategories as $sub) {
        $category_ids[] = $sub['category_id'];
      }

      $products = [];
      foreach ($category_ids as $cid) {
        $filter_data = [
          'filter_category_id' => $cid,
          'filter_status' => 1,
          'start' => 0,
          'limit' => 20
        ];
        $results = $this->model_catalog_product->getProducts($filter_data);

        // DEBUG FILTER AND RESULTS

        $this->load->model('tool/image');
        foreach ($results as $result) {
          $formattedProduct = $this->model_journal3_product->formatProduct($result);

          if (!$result['image'] || !is_file(DIR_IMAGE . $result['image'])) {
            $formattedProduct['thumb'] = $this->model_tool_image->resize('placeholder.png', 250, 250);
          }

          $products[] = $formattedProduct;
        }
      }

      // Remove or comment out debug
      // echo "<pre>CATEGORY: {$category['name']} - PRODUCT COUNT: " . (is_array($products) ? count($products) : 0) . "</pre>";

      if (!empty($products) && is_array($products)) {
        // Shuffle the results to randomize and then slice them.
        shuffle($products);
        $products = array_slice($products, 0, 8); // Keep 8 for logic, Twig limits to 4 visually.

        $data['custom_categories'][] = [
          'name' => $category['name'],
          'category_id' => $category['category_id'],
          'href' => $this->url->link('product/category', 'path=' . $category['category_id']),
          'products' => $products
        ];
      }
    }

    // Load standard layout parts
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['column_right'] = $this->load->controller('common/column_right');
    $data['content_top'] = $this->load->controller('common/content_top');
    $data['content_bottom'] = $this->load->controller('common/content_bottom');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');


    // echo "<pre>FINAL DATA:";
    // print_r($data['custom_categories'][0]['products']);
    // exit();
    $this->response->setOutput($this->load->view('common/home', $data));
  }
}