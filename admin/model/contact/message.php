<?php
class ModelContactMessage extends Model {
	public function getMessages() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal3_message ORDER BY date DESC");
		return $query->rows;
	}
}
