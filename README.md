
# Website đổi thẻ cào

* Website liên kết với website nhà mạng thông qua API gữi thẻ cào, mua thẻ cào và thanh toán tự động.


## Tác giả

- [@HHiepz](https://www.github.com/hhiepz)

## 🛠 Kỹ năng
Dự án này được xây dựng bằng các công nghệ sau:

* **PHP** - Xử lý logic backend và các yêu cầu của người dùng.
* **MySQL** - Quản lý cơ sở dữ liệu để lưu trữ và truy xuất thông tin thẻ.
* **HTML5 & CSS3** - Tạo dựng giao diện người dùng trực quan và thân thiện.
* **Bootstrap 5** - Tối ưu giao diện người dùng và đảm bảo tính đáp ứng trên nhiều thiết bị.
* **JavaScript & Ajax** - Tương tác động mà không cần tải lại trang.
* **cURL** - Gửi thông tin thẻ đến trang web chính.
## Yêu cầu

- PHP phiên bản thấp nhất: **8.2**
- Database server: **Khuyến nghị MySQL**
- Để tối ưu toàn bộ quá trình, vui lòng tham khảo hướng dẫn [Cài đặt CronJobs](./CRONJOB.md).

## Chạy cục bộ

Sao chép dự án

```bash
  https://github.com/HHiepz/cv_doi-the-cao.git
```

Tạo database và import dữ liệu file 

```bash
  database.sql
```

Cấu hình database tại file

```bash
  core/database.php
```
