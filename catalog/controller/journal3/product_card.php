<?php
class ControllerJournal3ProductCard extends Controller {
    public function index($product) {
        if (!isset($product['product_id'])) {
            return '';
        }

        return $this->load->view('journal3/product_card', [
            'product' => $product
        ]);
    }
}
