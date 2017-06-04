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
            "SELECT * FROM books";

        $result = $connection->query($sql);
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $book = new Book();
            $book->loadFromDB($connection, $row['id']);
            $books[] = $book;
        }
        echo json_encode($books);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_POST['author']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $author = $_POST['author'];
        $description = $_POST['description'];

        $book = new Book();
        $array = [];
        if ($book->create($connection, $name, $author, $description)) {
            $array['text'] = "Pomyślnie dodałeś książke";
            $array['status'] = "success";
        } else {
            $array['text'] = "Błąd podczas dodawania książki";
            $array['status'] = "add_error";
            echo json_encode($array);
        }
    } /* TODO zmodyfikować else {
        $array['text'] = "Wprowadź wszystkie dane";
        $array['status'] = "data_error";
        echo json_encode($array);
    }*/
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
            $array['status'] = "success";
        } else {
            $array['text'] = "Błąd podczas modyfikacji książki";
            $array['status'] = "add_error";
            echo json_encode($array);
        }
    }
}
