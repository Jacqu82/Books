$(function () {

    var dom = {
        $bookList: $('#book-list'),
    };


    function getBooks() {
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
                    booksHtml += '<li class="book collapsed" data-id="' + book.id + '">' +
                        '<p class="book-name"> <strong>' + book.name + '</strong></p>' + " " +
                        '<span class="delete">x</span>' +
                        '<div class="book-additional-data">' +
                        '</div>'
                    '</li>';
                });
                booksHtml += '</ul>';

                dom.$bookList.append(booksHtml);
            });
    }

    getBooks();

    $('#book-list').on('click', '.book-name', function () {
        var $book = $(this).parent();
        var bookId = $book.attr('data-id');
        if ($book.hasClass('collapsed')) {
            $book.removeClass('collapsed');

            $.get("api/books.php", {id: bookId})
                .done(function (data) {
                    // Zmienna data będzie zawierała zawartość echo json_encode($book);
                    // przekazaną do funkcji js'owej JSON.parse. Ona powoduje że ze zwykłego stringa zwracanego przez php
                    // staje się znowu tablicą obiektów, ale zrozumiałą dla języka js
                    // var books = [
                    //     {name: 'nazwa', description: 'opis'},
                    //     {name: '...', description: '...'},
                    //     // ...
                    // ];

                    var book = JSON.parse(data);
                    var bookDetailsHtml = '<p>' +
                        book.author +
                        ' ' +
                        book.description +
                        '</p>';
                    $book.find('.book-additional-data').html(bookDetailsHtml);
                });
        }
    });

    $('#add-book').on('submit', function(e){
       e.preventDefault();
       var $form = $(this);
        var name = $form.find('input[name=name]').val();

       var author = $form.find('input[name=author]').val();

       var description = $form.find('input[name=description]').val();

       $.post("api/books.php", {name: name, author: author, description: description }).done(function(data) {
           var status = JSON.parse(data);
           if (status.status == 'Success') {
                dom.$bookList.html(status.text);
                getBooks();
           }
       });
    });

    $('#book-list').on('click', '.delete', function (e) {
        e.preventDefault();
        var $book = $(this).parent();
        var bookId = $book.attr('data-id');

        $.ajax({
            url: "api/books.php",
            type: 'delete'
        }, {id: bookId})
            .done(function (data) {
                // var response = JSON.parse(data);
                // console.log(response);
            });
    });



});