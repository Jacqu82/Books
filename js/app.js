$(function () {

    var dom = {
        $bookList: $('#book-list'),
    };


    $.get("api/books.php")
        .done(function (data) {
            // Zmienna books będzie zawierała zawartość echo json_encode($books);
            // przekazaną do funkcji js'owej JSON.parse. Ona powoduje że ze zwykłego stringa zwracanego przez php
            // staje się znowu tablicą obiektów, ale zrozumiałą dla języka js
            // var books = [
            //     {name: 'nazwa', description: 'opis'},
            //     {name: '...', description: '...'},
            //     // ...
            // ];

            var books = JSON.parse(data),
                booksHtml = '<ul>';

            // Funkcja forEach przyjmuje jako argument funkcję, która w 1 argumencie przyjmuje nazwę zmiennej
            // do jakiej ma wpisywać pojedyncze wiersze z przetwarzanej zmiennej - tutaj books jest rozbijana na book
            books.forEach(function (book) {
                // Żeby nie modyfikować drzewa dom wielokrotnie modyfikuję je raz, ale po wytworzeniu całego markupu
                // ze wszystkimi elementami.
                booksHtml += '<li class="book">' +
                    '<p class="book-name"> <strong>' + book.name + ' </strong></p>' +
                    '<div class="book-additional-data">' +
                    '<span class="book-author"> ' + book.author + ' </span>' +
                    '<span class="book-description"> ' + book.description + ' </span>' +
                    '</div>'
                '</li>';
            });
            booksHtml += '</ul>';

            dom.$bookList.append(booksHtml);
        });

});