<?php

include_once 'src/connection.php';
include_once 'src/Book.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $connection->real_escape_string($_GET['id']);
        $sql = /** @lang text */
            "SELECT * From books WHERE id = $id";
        $result = $connection->query($sql);

        $book = new Book();
        $book->loadFromDB($connection, $id);
        echo json_encode($book);
    } else {
        $sql = /** @lang text */
            "SELECT id, name FROM books";
        $result = $connection->query($sql);
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_POST['author']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $author = $_POST['author'];
        $description = $_POST['description'];
        if ((strlen($name) > 0) && (strlen($author) > 0) && (strlen($description) > 0)) {
            $book = new Book();
            $array = [];
            if ($book->create($connection, $name, $author, $description)) {
                $array['text'] = "Pomyślnie dodałeś książke";
                $array['status'] = "Success";
                echo json_encode($array);
            } else {
                $array['text'] = "Błąd podczas dodawania książki";
                $array['status'] = "Error";
                echo json_encode($array);
            }
        } else {
            $array['text'] = "Wprowadź wszystkie dane";
            $array['status'] = "Error";
            echo json_encode($array);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['id']) && isset($_GET['name']) && isset($_GET['author']) && isset($_GET['description'])) {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $author = $_GET['author'];
        $description = $_GET['description'];

        $book = new Book();
        $book->loadFromDB($connection, $id);
        $book->setName($name);
        $book->setDescription($description);
        $book->setAuthor($author);

        $array = [];
        if ($book->update($connection)) {
            $array['text'] = "Pomyślnie zmodyfikowałeś książkę";
            $array['status'] = "Success";
        } else {
            $array['text'] = "Błąd podczas modyfikacji książki";
            $array['status'] = "Error";
        }
        echo json_encode($array);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $book = new Book();
        $book->loadFromDB($connection, $id);

        $array = [];
        if ($book->deleteFromDB($connection)) {
            $array['text'] = "Pomyślnie usunołeś książkę";
            $array['status'] = "Success";
            echo json_encode($array);
        } else {
            $array['text'] = "Bład podczas usuwania książki";
            $array['status'] = "Error";
            echo json_encode($array);
        }
    }
}
