<?php
class ControllerExtensionModuleCustomHomeCategoryProducts extends Controller {
    public function index($setting) {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $data['categories'] = [];

        // Set your category IDs here (manually or from admin setting)
        $category_ids = [59, 60, 61]; // Replace with your category IDs

        foreach ($category_ids as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);
            if ($category_info) {
                $filter_data = [
                    'filter_category_id' => $category_id,
                    'sort'               => 'p.date_added',
                    'order'              => 'DESC',
                    'start'              => 0,
                    'limit'              => 8
                ];

                $products = $this->model_catalog_product->getProducts($filter_data);
                $product_data = [];

                foreach ($products as $product) {
                    $product_data[] = [
                        'product_id' => $product['product_id'],
                        'thumb'      => $this->model_tool_image->resize($product['image'], 200, 200),
                        'name'       => $product['name'],
                        'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $this->session->data['currency']),
                        'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                    ];
                }

                $data['categories'][] = [
                    'name'     => $category_info['name'],
                    'href'     => $this->url->link('product/category', 'path=' . $category_id),
                    'products' => $product_data
                ];
            }
        }

        return $this->load->view('extension/module/custom_home_category_products', $data);
    }
}
