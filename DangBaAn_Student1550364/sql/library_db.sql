CREATE DATABASE library_db;
USE library;
	
CREATE TABLE authors (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    author_name VARCHAR(255) NOT NULL,
	    book_numbers INT NOT NULL
	);
	
CREATE TABLE categories (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    category_name VARCHAR(255) NOT NULL
	);
	
CREATE TABLE books (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    title VARCHAR(255) NOT NULL,
	    author_id INT NOT NULL,
	    category_id INT NOT NULL,
	    publisher VARCHAR(255) NOT NULL,
	    publish_year YEAR NOT NULL,
	    quantity INT NOT NULL,
	    FOREIGN KEY (author_id) REFERENCES authors(id),
	    FOREIGN KEY (category_id) REFERENCES categories(id)
	);
	
ALTER TABLE books MODIFY publish_year INT;


INSERT INTO authors (author_name, book_numbers) VALUES
('Nguyễn Nhật Ánh', 25),
('Nguyễn Huy Thiệp', 15),
('Bảo Ninh', 10),
('Hữu Ơn', 8),
('Tô Hoài', 20),
('Lê Lựu', 12),
('Nguyễn Khải', 18),
('Nguyễn Quang Sáng', 22),
('Vũ Hùng', 5),
('Mai Akira', 3),
('Phạm Đức', 6),
('Lâm Ngữ Đường', 13),
('Trí Lê', 7),
('Lê Minh Khuê', 17),
('Đoàn Minh Tuấn', 11);



INSERT INTO categories (category_name) VALUES
('Tiểu thuyết'),
('Trinh thám'),
('Khoa học viễn tưởng'),
('Lịch sử'),
('Kinh tế'),
('Giáo dục'),
('Tâm lý'),
('Văn học'),
('Nhân văn'),
('Kỹ thuật');



INSERT INTO books (title, author_id, category_id, publisher, publish_year, quantity) VALUES
('Tôi thấy hoa vàng trên cỏ xanh', 1, 1, 'NXB Trẻ', 2015, 100),
('Người đàn bà trên tầng thượng', 2, 2, 'NXB Hội Nhà Văn', 2017, 50),
('Nỗi buồn chiến tranh', 3, 3, 'NXB Quân đội Nhân dân', 1991, 75),
('Chí Phèo', 4, 1, 'NXB Tác phẩm Mới', 1960, 60),
('Dế Mèn phiêu lưu ký', 5, 1, 'NXB Kim Đồng', 1941, 120),
('Số đỏ', 6, 1, 'NXB Văn học', 1936, 80),
('Mùa thu vàng', 7, 1, 'NXB Văn hóa', 1999, 90),
('Rừng xà nu', 8, 4, 'NXB Giáo dục', 1985, 65),
('Những ngày xưa yêu dấu', 9, 1, 'NXB Sáng Tạo', 2000, 40),
('Chuyện người con gái Nam Xương', 10, 1, 'NXB Văn học', 1968, 55),
('Sống mãi với thủ đô', 11, 4, 'NXB Hà Nội', 1989, 70),
('Những tấm lòng cao cả', 12, 2, 'NXB Thanh Niên', 1995, 85),
('Điệp viên 007', 13, 3, 'NXB Công an Nhân dân', 2020, 90),
('Khát vọng xanh', 14, 7, 'NXB Giáo dục', 2018, 55),
('Hành trình về phương Đông', 15, 6, 'NXB Thời Đại', 2021, 100);


