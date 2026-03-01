<?php
class ControllerStartupSeoUrl extends Controller
{
    public function index()
    {
        // Add rewrite to url class
        if ($this->config->get("config_seo_url")) {
            $this->url->addRewrite($this);
        }

        // Decode URL
        if (isset($this->request->get["_route_"])) {
            $parts = explode("/", $this->request->get["_route_"]);

            // remove any empty arrays from trailing
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            foreach ($parts as $part) {
                $query = $this->db->query(
                    "SELECT * FROM " .
                        DB_PREFIX .
                        "seo_url WHERE keyword = '" .
                        $this->db->escape($part) .
                        "' AND store_id = '" .
                        (int) $this->config->get("config_store_id") .
                        "'"
                );


                // Journal Theme Modification
                if ($part && !$query->num_rows) {
                    $sql = "
                        SELECT CONCAT('journal_blog_category_id=', category_id) as query FROM " . DB_PREFIX . "journal3_blog_category_description
                        WHERE keyword = '" . $this->db->escape($part) . "'
                        UNION
                        SELECT CONCAT('journal_blog_post_id=', post_id) as query FROM " . DB_PREFIX . "journal3_blog_post_description
                        WHERE keyword = '" . $this->db->escape($part) . "'
                    ";
                    $query = $this->db->query($sql);
                }

                if (!$query->num_rows) {
                    $this->load->model('journal3/blog');
                    $journal_blog_keywords = $this->model_journal3_blog->getBlogKeywords();

                    if($part && is_array($journal_blog_keywords) && (in_array($part, $journal_blog_keywords))) {
                        $this->request->get['route'] = 'journal3/blog';
                        continue;
                    }
                }
                // End Journal Theme Modification
            
                if ($query->num_rows) {
                    $url = explode("=", $query->row["query"]);

                    if ($url[0] == "product_id") {
                        $this->request->get["product_id"] = $url[1];
                    }

                    if ($url[0] == 'journal_blog_post_id') {
                        $this->request->get['journal_blog_post_id'] = $url[1];
                        $this->request->get['route'] = 'journal3/blog/post';
                        continue; 
                    }

                    if ($url[0] == "category_id") {
                        if (!isset($this->request->get["path"])) {
                            $this->request->get["path"] = $url[1];
                        } else {
                            $this->request->get["path"] .= "_" . $url[1];
                        }
                    }

                   if ($url[0] == "manufacturer_id") {
    $this->request->get["manufacturer_id"] = $url[1];
    $this->request->get["route"] = "product/manufacturer/info"; // ADD THIS LINE
}


                    if ($url[0] == "information_id") {
                        $this->request->get["information_id"] = $url[1];
                    }

                    if (
                        $query->row["query"] &&
                        $url[0] != "information_id" &&
                        $url[0] != "manufacturer_id" &&
                        $url[0] != "category_id" &&
                        $url[0] != "product_id"
                    ) {
                        $this->request->get["route"] = $query->row["query"];
                    }
                } else {
                    $this->request->get["route"] = "error/not_found";

                    break;
                }
            }

            if (!isset($this->request->get["route"])) {
                if (isset($this->request->get["product_id"])) {
                    $this->request->get["route"] = "product/product";
                } elseif (isset($this->request->get["path"])) {
                    $this->request->get["route"] = "product/category";
                } elseif (isset($this->request->get["manufacturer_id"])) {
                    $this->request->get["route"] = "product/manufacturer/info";
                } elseif (isset($this->request->get["information_id"])) {
                    $this->request->get["route"] = "information/information";
                }
            }
        }
    }

    public function rewrite($link)
    {
        $url_info = parse_url(str_replace("&amp;", "&", $link));

        $url = "";

        $data = [];

        parse_str($url_info["query"], $data);

        foreach ($data as $key => $value) {

        	if ($key == "path") {
        $categories = explode("_", $value);

        foreach ($categories as $category) {
            $query = $this->db->query(
                "SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'category_id=" . (int) $category . "' AND store_id = '" . (int) $this->config->get("config_store_id") . "' AND language_id = '" . (int) $this->config->get("config_language_id") . "'"
            );

            if ($query->num_rows && $query->row["keyword"]) {
                $url .= "/" . $query->row["keyword"];
            } else {
                $url = "";
                break;
            }
        }

        // Remove category path from product URL for clean SEO
        if (isset($data["route"]) && $data["route"] == "product/product") {
            unset($data["path"]);
        }

        unset($data[$key]);
    }

            if (isset($data["route"])) {
                if ($data["route"] == "common/home") {
                    unset($data["route"]);
                    return $url_info["scheme"] .
                        "://" .
                        $url_info["host"] .
                        "/";
                }

                // Custom SEO for Order Info page
if ($data['route'] == 'account/order/info' && isset($data['order_id'])) {
    $url .= '/order-info/' . $data['order_id'];
    unset($data['route']);
    unset($data['order_id']);
}


                // if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                // 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

                // 	if ($query->num_rows && $query->row['keyword']) {
                // 		$url .= '/' . $query->row['keyword'];

                // 		unset($data[$key]);
                // 	}
                // }

                // if (
                //     ($data["route"] == "product/product" &&
                //         isset($data["product_id"])) ||
                //     ($data["route"] == "information/information" &&
                //         isset($data["information_id"])) ||
                //     ($data["route"] == "product/manufacturer/info" &&
                //         isset($data["manufacturer_id"]))
                // ) {
                //     $key =
                //         $data["route"] == "product/product"
                //             ? "product_id"
                //             : ($data["route"] == "information/information"
                //                 ? "information_id"
                //                 : "manufacturer_id");

                //     $query = $this->db->query(
                //         "SELECT * FROM " .
                //             DB_PREFIX .
                //             "seo_url WHERE `query` = '" .
                //             $this->db->escape($key . "=" . (int) $data[$key]) .
                //             "' AND store_id = '" .
                //             (int) $this->config->get("config_store_id") .
                //             "' AND language_id = '" .
                //             (int) $this->config->get("config_language_id") .
                //             "'"
                //     );

                //     if ($query->num_rows && $query->row["keyword"]) {
                //         $url .= "/" . $query->row["keyword"];
                //         unset($data[$key]);
                //     }
                // }

                if (
    ($data["route"] == "product/product" &&
        isset($data["product_id"])) ||
    ($data["route"] == "information/information" &&
        isset($data["information_id"])) ||
    ($data["route"] == "product/manufacturer/info" &&
        isset($data["manufacturer_id"]))
) {
    $key =
        $data["route"] == "product/product"
            ? "product_id"
            : ($data["route"] == "information/information"
                ? "information_id"
                : "manufacturer_id");

    $query = $this->db->query(
        "SELECT * FROM " .
            DB_PREFIX .
            "seo_url WHERE `query` = '" .
            $this->db->escape($key . "=" . (int) $data[$key]) .
            "' AND store_id = '" .
            (int) $this->config->get("config_store_id") .
            "' AND language_id = '" .
            (int) $this->config->get("config_language_id") .
            "'"
    );

    if ($query->num_rows && $query->row["keyword"]) {
        $url .= "/" . $query->row["keyword"];
        unset($data[$key]);

        //  Prevent manufacturer_id in product URLs
        if ($data["route"] == "product/product" && isset($data["manufacturer_id"])) {
            unset($data["manufacturer_id"]);
        }
    }
}


                // Journal3 Blog Post SEO
                
    // Journal3 Blog Post SEO
if ($data['route'] == 'journal3/blog/post' && isset($data['journal_blog_post_id'])) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'journal_blog_post_id=" . (int)$data['journal_blog_post_id'] . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

    if ($query->num_rows && $query->row['keyword']) {
        $url .= '/' . $query->row['keyword'];
        unset($data['route']);
        unset($data['journal_blog_post_id']);
    }
}

                if (
                    isset($data["route"]) &&
                    !in_array($data["route"], [
                        "product/product",
                        "information/information",
                        "product/manufacturer/info",
                    ]) &&
                    !isset($data["product_id"]) &&
                    !isset($data["information_id"]) &&
                    !isset($data["manufacturer_id"])
                ) {
                    $query = $this->db->query(
                        "SELECT * FROM " .
                            DB_PREFIX .
                            "seo_url WHERE `query` = '" .
                            $this->db->escape($data["route"]) .
                            "' AND store_id = '" .
                            (int) $this->config->get("config_store_id") .
                            "' AND language_id = '" .
                            (int) $this->config->get("config_language_id") .
                            "'"
                    );

                    if ($query->num_rows && $query->row["keyword"]) {
                        $url .= "/" . $query->row["keyword"];
                        unset($data["route"]);
                    }
                } elseif ($key == "path") {
                    $categories = explode("_", $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query(
                            "SELECT * FROM " .
                                DB_PREFIX .
                                "seo_url WHERE `query` = 'category_id=" .
                                (int) $category .
                                "' AND store_id = '" .
                                (int) $this->config->get("config_store_id") .
                                "' AND language_id = '" .
                                (int) $this->config->get("config_language_id") .
                                "'"
                        );

                        if ($query->num_rows && $query->row["keyword"]) {
                            $url .= "/" . $query->row["keyword"];
                        } else {
                            $url = "";
                            break;
                        }
                    }

                    // Remove category path from product URL for clean SEO
                    if (
                        isset($data["route"]) &&
                        $data["route"] == "product/product"
                    ) {
                        unset($data["path"]);
                    }

                    unset($data[$key]);
                }
            }
        }

        if ($url) {
            unset($data["route"]);

            $query = "";

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .=
                        "&" .
                        rawurlencode((string) $key) .
                        "=" .
                        rawurlencode(
                            is_array($value)
                                ? http_build_query($value)
                                : (string) $value
                        );
                }

                if ($query) {
                    $query = "?" . str_replace("&", "&amp;", trim($query, "&"));
                }
            }

            return $url_info["scheme"] .
                "://" .
                $url_info["host"] .
                (isset($url_info["port"]) ? ":" . $url_info["port"] : "") .
                str_replace("/index.php", "", $url_info["path"]) .
                $url .
                $query;
        } else {
            return $link;
        }
    }
}
