<?php

include_once 'src/config.php';
include_once 'src/Book.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM books WHERE id=$id";
        $result = $conn->query($sql);
        $book = new Book();
        $book->loadFromDB($conn, $id);
        
        echo json_encode($book);
    } else {
        $sql_id = "SELECT id FROM books ORDER BY author, name";
        $result = $conn->query($sql_id);
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $book = new Book();
            $book->loadFromDB($conn, $row['id']);
            $books[] = $book;
        }
        
        echo json_encode($books);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [];
    
    if (!empty($_POST['author']) && !empty($_POST['name']) && !empty($_POST['book_description']) && !empty($_POST['genre'])) {
        
        $author = $_POST['author'];
        $name = $_POST['name'];
        $book_description = $_POST['book_description'];
        $genre = $_POST['genre'];
        
        $newBook = new Book();
        if ($newBook->create($conn, $name, $book_description, $author, $genre)) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }
        echo json_encode($data);
    } else {
        $data['message'] = "Nie mamy wszystkich informacji!";
        $data['success'] = false;
        echo json_encode($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $del_vars);
    if (isset($del_vars['id'])) {
        $id = $del_vars['id'];
        $sql = "SELECT *  FROM books WHERE id=$id";
        
        $result = $conn->query($sql);
        $book = new Book();
        $book->deleteFromDB($conn, $id);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $data = [];
    $id = $put_vars['id'];
    $newBook = new Book();
    $newBook->loadFromDB($conn, $id);
    
    if (!empty($put_vars['author'])) {
        $author = $put_vars['author'];
    } else {
        $author = $newBook->getAuthor();
    }
    
    if (!empty($put_vars['name'])) {
        $name = $put_vars['name'];
    } else {
        $name = $newBook->getName();
    }
    
    if (!empty($put_vars['book_description'])) {
        $book_description = $put_vars['book_description'];
    } else {
        $book_description = $newBook->getBook_decription();
    }
    
    if (!empty($put_vars['genre'])) {
        $genre = $put_vars['genre'];
    } else {
        $genre = $newBook->getGenre();
    }
    
    if ($newBook->update($conn, $name, $author, $book_description, $genre) == true) {
        $data['success'] = true;
    } else {
        $data['success'] = false;
    }
    echo json_encode($data);
}


?>

