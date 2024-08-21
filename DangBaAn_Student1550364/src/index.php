<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xác định số sách trên mỗi trang
$books_per_page = 10;

// Xác định trang hiện tại từ tham số GET, mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Đảm bảo trang không nhỏ hơn 1
$offset = ($page - 1) * $books_per_page;

// Xử lý tìm kiếm
$search_query = "";
$search_sql = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    $search_sql = " AND (books.title LIKE '%$search_query%' 
                    OR authors.author_name LIKE '%$search_query%' 
                    OR categories.category_name LIKE '%$search_query%')";
}

// Tìm tổng số sách để tính tổng số trang
$total_books_sql = "SELECT COUNT(*) AS total FROM books 
                    JOIN authors ON books.author_id = authors.id 
                    JOIN categories ON books.category_id = categories.id 
                    WHERE 1=1 $search_sql";
$total_books_result = $conn->query($total_books_sql);
$total_books = $total_books_result->fetch_assoc()['total'];
$total_pages = ceil($total_books / $books_per_page);

// Truy vấn để lấy danh sách sách với phân trang và tìm kiếm
$sql = "SELECT books.*, authors.author_name, categories.category_name 
        FROM books
        JOIN authors ON books.author_id = authors.id
        JOIN categories ON books.category_id = categories.id
        WHERE 1=1 $search_sql
        ORDER BY books.title
        LIMIT $offset, $books_per_page";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Căn giữa thanh phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Library Management</h2>
    
    <!-- Search Form -->
    <form method="get" class="input-group mb-2">
        <input type="text" name="search" class="form-control" placeholder="Search books, authors, categories..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
    
    <!-- Add Book Button -->
    <a href="add_book.php" class="btn btn-primary mb-3">Add New Book</a>
    
    <!-- Book List -->
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Publisher</th>
            <th>Year</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                <td><?php echo htmlspecialchars($row['publish_year']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>
                    <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this book?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiển thị phân trang -->
    <nav>
        <ul class="pagination">
            <!-- Previous Page Link -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            <?php endif; ?>
            
            <!-- Page Number Links -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Next Page Link -->
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const confirmDelete = document.getElementById('confirmDelete');
        confirmDelete.href = 'delete_book.php?id=' + id;
    });
</script>
</body>
</html>
