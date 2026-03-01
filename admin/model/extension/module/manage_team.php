<?php
// File: admin/model/extension/module/manage_team.php
class ModelExtensionModuleManageTeam extends Model {
    public function addTeamMember($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "team_member SET 
            name = '" . $this->db->escape($data['name']) . "', 
            designation = '" . $this->db->escape($data['designation']) . "', 
            expertise = '" . $this->db->escape($data['expertise']) . "', 
            type = '" . $this->db->escape($data['type']) . "', 
            linkedin = '" . $this->db->escape($data['linkedin']) . "', 
            twitter = '" . $this->db->escape($data['twitter']) . "', 
            facebook = '" . $this->db->escape($data['facebook']) . "', 
            email = '" . $this->db->escape($data['email']) . "', 
            image = '" . $this->db->escape($data['image']) . "'");
    }

    public function editTeamMember($team_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "team_member SET 
            name = '" . $this->db->escape($data['name']) . "', 
            designation = '" . $this->db->escape($data['designation']) . "', 
            expertise = '" . $this->db->escape($data['expertise']) . "', 
            type = '" . $this->db->escape($data['type']) . "', 
            linkedin = '" . $this->db->escape($data['linkedin']) . "', 
            twitter = '" . $this->db->escape($data['twitter']) . "', 
            facebook = '" . $this->db->escape($data['facebook']) . "', 
            email = '" . $this->db->escape($data['email']) . "', 
            image = '" . $this->db->escape($data['image']) . "' 
            WHERE team_id = '" . (int)$team_id . "'");
    }

    public function deleteTeamMember($team_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "team_member WHERE team_id = '" . (int)$team_id . "'");
    }

    public function getTeamMembers() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "team_member ORDER BY name");
        return $query->rows;
    }

    public function getTeamMember($team_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "team_member WHERE team_id = '" . (int)$team_id . "'");
        return $query->row;
    }
}
