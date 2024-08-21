<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author_name = $_POST['author_name'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $publish_year = $_POST['publish_year'];
    $quantity = $_POST['quantity'];

    // Tìm author_id từ author_name
    $author_result = $conn->query("SELECT id FROM authors WHERE author_name = '$author_name'");
    $author_row = $author_result->fetch_assoc();
    $author_id = $author_row ? $author_row['id'] : null;

    // Nếu tác giả không tồn tại, thêm tác giả mới
    if (!$author_id) {
        $conn->query("INSERT INTO authors (author_name, book_numbers) VALUES ('$author_name', 0)");
        $author_id = $conn->insert_id; // Lấy ID của tác giả mới được thêm
    }

    // Thực hiện truy vấn SQL để thêm sách vào cơ sở dữ liệu
    $sql = "INSERT INTO books (title, author_id, category_id, publisher, publish_year, quantity) 
            VALUES ('$title', $author_id, $category_id, '$publisher', $publish_year, $quantity)";

    if ($conn->query($sql) === TRUE) {
        // Cập nhật số lượng sách của tác giả
        $conn->query("UPDATE authors SET book_numbers = book_numbers + 1 WHERE id = $author_id");
        // Chuyển hướng đến trang index.php sau khi thêm thành công
        header("Location: /DangBaAn_Student1550364/src/index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Add New Book</h2>
    
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="author_name" class="form-label">Author</label>
            <input type="text" class="form-control" id="author_name" name="author_name" list="authors" required>
            <datalist id="authors">
                <?php
                $result = $conn->query("SELECT author_name FROM authors");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['author_name'] . "'>";
                }
                ?>
            </datalist>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <?php
                $result = $conn->query("SELECT id, category_name FROM categories");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="publisher" class="form-label">Publisher</label>
            <input type="text" class="form-control" id="publisher" name="publisher" required>
        </div>
        <div class="mb-3">
            <label for="publish_year" class="form-label">Year</label>
            <input type="number" class="form-control" id="publish_year" name="publish_year" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
</div>
</body>
</html>
