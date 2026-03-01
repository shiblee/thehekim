<?php
class ControllerContactMessage extends Controller {
    public function index() {
        $this->load->language('contact/message');
        $this->document->setTitle('Contact Form Submissions');
        $this->load->model('contact/message');

        $data['messages'] = $this->model_contact_message->getMessages();

        
        foreach ($data['messages'] as &$message) {
            $message['fields'] = json_decode($message['fields'], true);
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('contact/message_list', $data));
    }
}

