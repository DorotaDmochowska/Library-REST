
$(document).ready(function () {

    var endpoint = "./api/books.php";

    var updateForm = '<form class="update_form" action="./api/books.php" method="POST">\n\
                                                        <label>autor:</label><input type="text" name="update_author"/></input>\n\
                                                        <label>tytuł:</label><input type="text" name="update_title"/></input>\n\
                                                        <label>opis fabuły:</label><textarea maxlength="1000" name="update_book_desc"/></textarea>\n\
                                                        <label>gatunek:</label><input type="text" name="update_genre"></input><br>\n\
                                                        <button name="submit" id="btn2">Aktualizuj informacje</button></form>';

    var tableHeading = $('.table_heading').clone();

// funkcja, która wczytuje listę wszystkich książek z bazy danych
    function loadAllBooks() {
        $.get(endpoint, function (json) {
            var books = $.parseJSON(json);
            for (var i = 0; i < books.length; i++) {
                var book = $('<table><tr><td>'
                        + books[i].author
                        + '</td><td class="book_title">'
                        + books[i].name
                        + '</td><td>' + books[i].genre
                        + '<td class="remove_book"  data-book-id="' + books[i].id + '">usuń</td>'
                        + '</td></tr></table>'
                        + '<div class="details"  data-book-id="' + books[i].id + '">'
                        + '<div class="book_desc">' + books[i].book_desc + '</div>'
                        + '<div class="update_book" data-book-id="' + books[i].id + '"></div></div>');
                $('#books').append($(book));
            }
        });
    }

//wywołanie funkcji - wyświetla listę książek na stronie
    loadAllBooks();

//po kliknięciu na tytuł rozwija pole z opisem książki
    $(document).on('click', ".book_title", function () {

        var detailsDiv = $(this).parentsUntil('#books').next();
        detailsDiv.slideToggle(500);

        var updateBookDiv = detailsDiv.find('.update_book');
        var bookId = detailsDiv.data("book-id");
        var newEndpoint = endpoint + "?id=" + bookId;

        $.get(newEndpoint, function (json) {
            var book = $.parseJSON(json);
            $(updateBookDiv).html(updateForm);
        });
    });

// tworzenie nowej książki za pomocą formularza
    $('form.add_form').submit(function (event) {
        var formData = {
            'author': $('input[name=author]').val(),
            'title': $('input[name=title]').val(),
            'book_desc': $('textarea[name=book_desc]').val(),
            'genre': $('input[name=genre]').val()
        };
        $.ajax({
            type: 'POST',
            url: endpoint,
            data: formData,
            dataType: 'json',
            encode: true
        })
                .done(function (data) {
                    if (data.success === true) {
                        $('#books').html(tableHeading);
                        loadAllBooks();
                        $('form').trigger("reset");
                    }
                });
        event.preventDefault();
    });

// aktualizacja informacji o danej książce
    $(document).on('click', '#btn2', function (event) {
        var bookId = $(this).parent().parent().data('book-id');
        event.preventDefault();

        var formData = {
            'id': bookId,
            'author': $('input[name=update_author]').val(),
            'title': $('input[name=update_title]').val(),
            'book_desc': $('textarea[name=update_book_desc]').val(),
            'genre': $('input[name=update_genre]').val()
        };

        $.ajax({
            type: 'PUT',
            url: endpoint,
            data: formData,
            dataType: 'json'
        })
                .done(function (json) {
                    $('#books').html(tableHeading);
                    loadAllBooks();
                    $('#btn2').trigger("reset");
                });
    });

//po kliknięciu na "remove" zostaje usunięta ksiazka o wskazanym id
    $(document).on('click', '.remove_book', function () {
        var bookId = $(this).data("book-id");

        $.ajax({
            type: 'DELETE',
            url: endpoint,
            data: {id: bookId},
            dataType: ''
        })
                .done(function () {
                    $('#books').html(tableHeading);
                    loadAllBooks();
                });
    });

});

