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
                var books = JSON.parse(data),
                    booksHtml = '<ul>';
                // Funkcja forEach przyjmuje jako argument funkcję, która w 1 argumencie przyjmuje nazwę zmiennej
                // do jakiej ma wpisywać pojedyncze wiersze z przetwarzanej zmiennej - tutaj books jest rozbijana na book
                books.forEach(function (book) {
                    // Żeby nie modyfikować drzewa dom wielokrotnie modyfikuję je raz, ale po wytworzeniu całego markupu
                    // ze wszystkimi elementami.
                    booksHtml += '<li class="book collapsed" data-id="' + book.id + '">' +
                        '<p class="book-name"> <strong>' + book.name + '</strong></p>' + " " +
                        '<div class="book-additional-data">' +
                        '</div>' +
                        '<p class="delete">x</p>' +
                        '</li>';
                });
                booksHtml += '</ul>';
                dom.$bookList.html(booksHtml);
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
                    var book = JSON.parse(data);
                    var bookDetailsHtml = '<p>' +
                        book.author +
                        '<br/>' +
                        book.description +
                        '</p>' +
                        '<form>' +
                        '<input type="text" class="name" value=" ' + book.name + '"><br>' +
                        '<input type="text" class="author" value=" ' + book.author + '"><br>' +
                        '<input type="text" class="description" value=" ' + book.description + '"><br>' +
                        '<input type="submit" class="modify" value="Aktualizuj">' +
                        '</form>';
                    $book.find('.book-additional-data').html(bookDetailsHtml);
                });
        } else {
            $book.find('.book-additional-data').toggle();
        }
    });

    $('#book-list').on('click', '.modify', function (e) {
        e.preventDefault();
        var $book = $(this).parents('.book');
        var bookId = $book.attr('data-id'),
            bookAuthor = $book.find('.author').val(),
            bookName = $book.find('.name').val(),
            bookDescription = $book.find('.description').val();

        $.ajax({
            url: "api/books.php?id=" + bookId +
            '&author=' + bookAuthor +
            '&name=' + bookName +
            '&description=' + bookDescription,
            type: 'put',
        })
            .done(function (response) {
                var status = JSON.parse(response);
                if (status.status === 'Success') {
                    if (status.status === 'Success') {
                        if (status.status == 'Success') {
                            dom.$bookList.html(status.text);
                            setTimeout(function () {
                                getBooks();
                            }, 2000);
                        }
                    }
                }
            });
    });

    $('#add-book').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var name = $form.find('input[name=name]').val();
        var author = $form.find('input[name=author]').val();
        var description = $form.find('input[name=description]').val();

        $.post("api/books.php", {name: name, author: author, description: description}).done(function (data) {
            var status = JSON.parse(data);
            if (status.status == 'Success') {
                dom.$bookList.html(status.text);
                setTimeout(function () {
                    getBooks();
                }, 2000);
            }
        });
    });

    $('#book-list').on('click', '.delete', function (e) {
        e.preventDefault();
        var $book = $(this).parent();
        var bookId = $book.attr('data-id');

        $.ajax({
            url: "api/books.php?id=" + bookId,
            type: 'delete',
        })
            .done(function (response) {
                var status = JSON.parse(response);
                if (status.status === 'Success') {
                    if (status.status == 'Success') {
                        dom.$bookList.html(status.text);
                        setTimeout(function () {
                            getBooks();
                        }, 2000);
                    }
                }
            });
    });
});