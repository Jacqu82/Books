<?php

class Book implements JsonSerializable
{
    private $id;
    private $name;
    private $author;
    private $description;

    public function __construct()
    {
        $this->id = -1;
        $this->name = '';
        $this->author = '';
        $this->description = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function loadFromDB(mysqli $connection, $id)
    {
        $id = $connection->real_escape_string($id);
        $sql = /** @lang text */
            "SELECT * FROM books WHERE id = $id";

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->author = $row['author'];
            $this->description = $row['description'];
            return true;
        }
        return false;
    }

    public function create(mysqli $connection, $name, $author, $description)
    {
        $name = $connection->real_escape_string($name);
        $author = $connection->real_escape_string($author);
        $description = $connection->real_escape_string($description);
        $sql = /** @lang text */
            "INSERT INTO books (name, author, description) VALUES ('$name', '$author', '$description')";

        $result = $connection->query($sql);
        if ($result) {
            $this->id = $connection->insert_id;
            $this->name = $name;
            $this->author = $author;
            $this->description = $description;
            return true;
        }
        return false;
    }

    public function update(mysqli $connection)
    {
        $id = $connection->real_escape_string($this->id);
        $name = $connection->real_escape_string($this->name);
        $author = $connection->real_escape_string($this->author);
        $description = $connection->real_escape_string($this->description);
        $sql = /** @lang text */
            "UPDATE books SET name = '$name', author = '$author', description = '$description' WHERE id = $id";

        $result = $connection->query($sql);
        if ($result) {
            $this->name = $name;
            $this->author = $author;
            $this->description = $description;
            return true;
        }
        return false;
    }

    public function deleteFromDB(mysqli $connection)
    {
        $id = $connection->real_escape_string($this->id);
        $sql = /** @lang text */
            "DELETE FROM books WHERE id = $id";

        $result = $connection->query($sql);
        if ($result) {
            $this->id = -1;
            $this->name = '';
            $this->author = '';
            $this->description = '';
            return true;
        }
        return false;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'description' => $this->description
        ];
    }
}
