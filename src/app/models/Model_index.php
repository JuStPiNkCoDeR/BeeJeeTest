<?php

const DB_HOST = 'mysql'; // Using docker service name
const DB_USER = 'root';
const DB_PASSWORD = 'p@ssWard';
const DB_NAME = 'beejee';
const DB_PORT = 3306;

const ENABLE_ORDERS = ['ID', 'username', 'email', 'isCompleted'];
const ENABLE_ORDER_DIRECTIONS = ['ASC', 'DESC'];


class Model_index extends Model
{
    private $isAdmin = false;

    /**
     * @return false|mysqli
     * @throws Error
     */
    private function dbConnect()
    {
        $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

        if (!$mysqli) {
            $errorMessage = "Ошибка: Невозможно установить соединение с MySQL.\n" .
                "Код ошибки errno: " . mysqli_connect_errno() . "\n" .
                "Текст ошибки error: " . mysqli_connect_error() . "\n";

            throw new Error($errorMessage);
        }

        return $mysqli;
    }

    /**
     * @param mysqli $mysqli
     * @param string $queryString
     * @return bool|mysqli_result
     * @throws Error
     */
    private function query($mysqli, $queryString)
    {
        $result = mysqli_query(
            $mysqli,
            $queryString
        );

        if (!$result) {
            $errorMessage = "Error: Can't proceed a query.\n" .
                "Error number: " . mysqli_errno($mysqli) . "\n" .
                "Error message: " . mysqli_error($mysqli) . "\n";

            throw new Error($errorMessage);
        }

        return $result;
    }

    /**
     * @param $args
     * @return array
     * @throws Error
     */
    public function getData($args)
    {
        $pageNumber = 1;
        $limitPerPage = 3;
        $orderedBy = 'ID';
        $orderDirection = 'ASC';

        if (isset($args['limit'])) {
            $limitPerPage = $args['limit'];
        }

        if (isset($args['page'])) {
            $pageNumber = $args['page'];
        }

        if (isset($args['orderBy']) && (in_array($args['orderBy'], ENABLE_ORDERS))) {
            $orderedBy = $args['orderBy'];
        }

        if (isset($args['orderDirection']) && (in_array($args['orderDirection'], ENABLE_ORDER_DIRECTIONS))) {
            $orderDirection = $args['orderDirection'];
        }

        $rowOffset = ($pageNumber - 1) * $limitPerPage;

        $mysqli = $this->dbConnect();

        $result = $this->query($mysqli, "SELECT * FROM tasks ORDER BY $orderedBy $orderDirection LIMIT $rowOffset, $limitPerPage;");

        $data = [];
        $tasks = [];

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($tasks, [
                'ID' => $row['ID'],
                'username' => $row['username'],
                'email' => $row['email'],
                'description' => $row['description'],
                'isCompleted' => $row['isCompleted'],
                'editedByAdmin' => $row['editedByAdmin'],
            ]);
        }

        $data['tasks'] = $tasks;
        $data['page'] = $pageNumber;

        $result = $this->query(
            $mysqli,
            "SELECT COUNT(*) FROM tasks"
        );

        mysqli_close($mysqli);

        $allTasksAmount = mysqli_fetch_array($result)[0];

        $data['pagesAmount'] = $allTasksAmount / $limitPerPage;

        if ($allTasksAmount % $limitPerPage != 0) {
            $data['pagesAmount']++;
        }

        $data['isAdmin'] = isset($_SESSION['signed']) && $_SESSION['signed'] == 1;
        $data['orderBy'] = $orderedBy;
        $data['orderDirection'] = $orderDirection;

        return $data;
    }

    public function addData($data)
    {
        $username = htmlspecialchars(trim($data['username']));
        $email = htmlspecialchars(trim($data['email']));
        $description = htmlspecialchars(trim($data['description']));

        if (!preg_match("/^.+@.+$/", $email)) {
            throw new Error("Incorrect email format.");
        }

        $mysqli = $this->dbConnect();

        $escapedUsername = mysqli_real_escape_string($mysqli, $username);
        $escapedEmail = mysqli_real_escape_string($mysqli, $email);
        $escapedDescription = mysqli_real_escape_string($mysqli, $description);

        $this->query(
            $mysqli,
            "INSERT INTO tasks (username, email, description) VALUES ('$escapedUsername', '$escapedEmail', '$escapedDescription');"
        );

        mysqli_close($mysqli);
    }

    public function updateData($data)
    {
        $description = htmlspecialchars(trim($data['description']));
        $completed = isset($data['completed']) ? 1 : 0;
        $ID = $data['ID'];

        $mysqli = $this->dbConnect();

        $escapedDescription = mysqli_escape_string($mysqli, $description);

        $this->query(
            $mysqli,
            "UPDATE tasks SET description = '$escapedDescription', isCompleted = $completed, editedByAdmin = 1 WHERE ID = $ID;"
        );

        mysqli_close($mysqli);
    }

    public function enableAdminMode()
    {
        $_SESSION['signed'] = 1;
    }

    public function disableAdminMode()
    {
        unset($_SESSION['signed']);
    }
}