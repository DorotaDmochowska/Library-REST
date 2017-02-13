<?php

//CREATE TABLE books (
//    id int(10) AUTO_INCREMENT NOT NULL ,
//    author varchar(255) NOT NULL,
//    name varchar(255) NOT NULL,
//    book_description text NOT NULL,
//    genre varchar(60) NOT NULL,
//    PRIMARY KEY (id)
//    );

class Book implements JsonSerializable {
    private $id;
    private $name;
    private $author;
    private $book_decription;
    private $genre;
    
    public function __construct($name="", $author="", $book_decsription="", $genre="") {
        $this->id = -1;
        $this->name = $name;
        $this->author = $author;
        $this->book_decription = $book_decsription;
        $this->genre = $genre;
    }
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getAuthor() {
        return $this->author;
    }

    function getBook_decription() {
        return $this->book_decription;
    }

    function getGenre() {
        return $this->genre;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    function setBook_decription($book_decription) {
        $this->book_decription = $book_decription;
        return $this;
    }

    function setGenre($genre) {
        $this->genre = $genre;
        return $this;
    }
    
    public function loadFromDB($conn, $id) {
        $safe_id = $conn->real_escape_string($id);
        $sql = "SELECT name, author, book_description, genre FROM books WHERE id=$safe_id";
        
        if($result = $conn->query($sql)) {
            
            $row = $result->fetch_assoc();
            $this->name = $row['name'];
            $this->author = $row['author'];
            $this->book_decription = $row['book_description'];
            $this->genre = $row['genre'];
            $this->id = $id;
            
            return true;
        } else {
            return false;
        }
    }
    
    public function create ($conn, $name, $author, $book_description, $genre) {
        $safe_name = $conn->real_escape_string($name);
        $safe_author = $conn->real_escape_string($author);
        $safe_book_description = $conn->real_escape_string($book_description);
        $safe_genre = $conn->real_escape_string($genre);
        
        $sql = "INSERT INTO books (name, author, book_description, genre) VALUES('$safe_name', '$safe_author', '$safe_book_description', '$safe_genre')";
        
        if($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update ($conn, $name, $author, $book_description, $genre) {
        $safe_name = $conn->real_escape_string($name);
        $safe_author = $conn->real_escape_string($author);
        $safe_book_description = $conn->real_escape_string($book_description);
        $safe_genre = $conn->real_escape_string($genre);
        
         $sql = "UPDATE books SET name='$safe_name', author='$safe_author', book_description='$book_description', gere='$genre' WHERE id=$this->id";
         
         if ($conn->query($sql)) {
             return true;
         } else {
             return false;
         }
    }
    
    public function deleteFromDB($conn, $id) {
        $safe_id = $conn->real_escape_string($id);
        
        $sql = "DELETE FROM books WHERE id=$safe_id";
        
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'book_description' => $this->book_decription,
            'genre' => $this->genre
                ];
    }

}

?>