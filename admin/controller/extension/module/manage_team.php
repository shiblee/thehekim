<?php
// File: admin/controller/extension/module/manage_team.php
class ControllerExtensionModuleManageTeam extends Controller {
    private $error = [];

    public function index() {
        $this->load->language('extension/module/manage_team');
        $this->document->setTitle('Manage Team');
        $this->load->model('extension/module/manage_team');

        $this->getList();
    }

    public function add() {
        $this->load->language('extension/module/manage_team');
        $this->document->setTitle('Add Team Member');
        $this->load->model('extension/module/manage_team');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                $filename = basename(html_entity_decode($_FILES['image_file']['name'], ENT_QUOTES, 'UTF-8'));
                $upload_path = DIR_IMAGE . 'catalog/team/' . $filename;
                move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path);
                $this->request->post['image'] = 'catalog/team/' . $filename;
            }

            $this->model_extension_module_manage_team->addTeamMember($this->request->post);
            $this->response->redirect($this->url->link('extension/module/manage_team', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/module/manage_team');
        $this->document->setTitle('Edit Team Member');
        $this->load->model('extension/module/manage_team');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                $filename = basename(html_entity_decode($_FILES['image_file']['name'], ENT_QUOTES, 'UTF-8'));
                $upload_path = DIR_IMAGE . 'catalog/team/' . $filename;
                move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path);
                $this->request->post['image'] = 'catalog/team/' . $filename;
            }

            $this->model_extension_module_manage_team->editTeamMember($this->request->get['team_id'], $this->request->post);
            $this->response->redirect($this->url->link('extension/module/manage_team', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/module/manage_team');
        $this->load->model('extension/module/manage_team');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $team_id) {
                $this->model_extension_module_manage_team->deleteTeamMember($team_id);
            }
            $this->response->redirect($this->url->link('extension/module/manage_team', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getList();
    }

    protected function getList() {
        $this->document->setTitle('Team List');
        $data['heading_title'] = 'Team Members';
        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('extension/module/manage_team');
        $data['members'] = $this->model_extension_module_manage_team->getTeamMembers();

        $data['add'] = $this->url->link('extension/module/manage_team/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('extension/module/manage_team/delete', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/manage_team_list', $data));
    }

    protected function getForm() {
        $data['heading_title'] = 'Team Member Form';
        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->get['team_id'])) {
            $team_id = $this->request->get['team_id'];
            $this->load->model('extension/module/manage_team');
            $member = $this->model_extension_module_manage_team->getTeamMember($team_id);
        } else {
            $member = [
                'name' => '',
                'designation' => '',
                'expertise' => '',
                'type' => 'product',
                'linkedin' => '',
                'twitter' => '',
                'facebook' => '',
                'email' => '',
                'image' => ''
            ];
        }

        foreach ($member as $key => $value) {
            $data[$key] = isset($this->request->post[$key]) ? $this->request->post[$key] : $value;
        }

        $data['action'] = isset($team_id) ? $this->url->link('extension/module/manage_team/edit', 'user_token=' . $this->session->data['user_token'] . '&team_id=' . $team_id, true) : $this->url->link('extension/module/manage_team/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['back'] = $this->url->link('extension/module/manage_team', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/manage_team_form', $data));
    }
}
