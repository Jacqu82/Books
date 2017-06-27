<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Books</title>
</head>
<body>
<div>
    <form action="api/books.php" id="add-book" method="POST">
        <label>Tytuł książki:
            <input type="text" name="name"/></label><br/><br/>
        <label>Imię i nazwisko autora:
            <input type="text" name="author"/></label><br/><br/>
        <label>Opis ksiązki:
            <input type="text" name="description"/></label><br/><br/>
        <input type="submit" value="Dodaj książke"/>
    </form>
</div>
<div>
    <h2>Lista książek</h2>
    <div id="book-list"></div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<script src="js/app.js"></script>
</body>
</html>