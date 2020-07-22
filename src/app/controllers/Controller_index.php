<?php


class Controller_index extends Controller
{
    function __construct()
    {
        parent::__construct();

        $this->model = new Model_index();
    }

    function action_index($query)
    {
        $data = null;

        try {
            $data = $this->model->getData($query);
        } catch (Error $error) {
            echo $error->getMessage();
        }

        $this->view->generate('index_view.php', 'template_view.php', $data);
    }

    function action_create($query, $postData)
    {
        try {
            $this->model->addData($postData);
        } catch (Error $error) {
            echo $error->getMessage();
        }

        $data = null;

        $this->action_index($query);
    }

    function action_signIn()
    {
        $this->view->generate('signIn_view.php', 'template_view.php');
    }

    function action_admin($query, $postData)
    {
        if ($postData['login'] == 'admin' && $postData['password'] == '123') {
            $this->model->enableAdminMode();
            $this->action_index($query);
        } else {
            $this->view->generate('signIn_view.php', 'template_view.php', 'denied');
        }
    }

    function action_update($query, $postData)
    {
        if (!isset($_SESSION['signed']) || $_SESSION['signed'] == 0) {
            $this->action_signIn();
            return;
        }
        $this->model->updateData($postData);

        $this->action_index($query);
    }

    function action_logOut($query)
    {
        $this->model->disableAdminMode();

        $this->action_index($query);
    }
}