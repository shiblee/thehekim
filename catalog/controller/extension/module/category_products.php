<?php
class ControllerExtensionModuleCategoryProducts extends Controller {
    public function index() {
        $this->load->language('extension/module/category_products');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $data['categories'] = [];

        $categories = $this->model_catalog_category->getCategories(0); // top-level categories

        foreach ($categories as $category) {
            $category_data = [
                'category_id' => $category['category_id'],
                'name'        => $category['name'],
                'products'    => []
            ];

            $sub_categories = $this->model_catalog_category->getCategories($category['category_id']);

            foreach ($sub_categories as $sub) {
                $filter_data = [
                    'filter_category_id' => $sub['category_id'],
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
                        'price'      => $this->currency->format($product['price'], $this->session->data['currency']),
                        'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                    ];
                }

                if ($product_data) {
                    $category_data['products'][] = [
                        'subcategory_name' => $sub['name'],
                        'products'         => $product_data
                    ];
                }
            }

            if (!empty($category_data['products'])) {
                $data['categories'][] = $category_data;
            }
        }

        return $this->load->view('extension/module/category_products', $data);
    }
}
