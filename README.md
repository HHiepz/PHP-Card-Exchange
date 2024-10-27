
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
- Để tối ưu toàn bộ quá trình, vui lòng tham khảo hướng dẫn [Cài đặt CronJobs](?tab=readme-ov-file#%EF%B8%8F-t%E1%BB%B1-%C4%91%E1%BB%99ng-h%C3%B3a-quy-tr%C3%ACnh).

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
## ⏱️ Tự động hóa quy trình (khi dùng trên hosting)

Để tối ưu các quy trình tự động của website, cấu hình các cron jobs sau để đảm bảo các tác vụ được thực hiện đúng lịch trình:

### Cấu hình các Cron Jobs

1. **auto_run_1_second.php**  
   - Mô tả: Xử lý các tác vụ cần chạy liên tục.
   - Tần suất: Mỗi phút.  
   - Câu lệnh cron: `* * * * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_1_second.php`

2. **auto_run_5_second.php**  
   - Mô tả: Xử lý tác vụ cần mỗi 5 giây.
   - Tần suất: Mỗi 5 phút.  
   - Câu lệnh cron: `*/5 * * * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_5_second.php`

3. **auto_run_morning.php**  
   - Mô tả: Chạy vào buổi sáng.
   - Tần suất: Hằng ngày vào 00:00.  
   - Câu lệnh cron: `0 0 * * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_morning.php`

4. **auto_run_firth_day_month.php**  
   - Mô tả: Chạy vào ngày đầu tháng.
   - Tần suất: Ngày đầu tháng.  
   - Câu lệnh cron: `0 0 1 * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_firth_day_month.php`

5. **auto_run_firth_day_month_5_second.php**  
   - Mô tả: Xử lý tác vụ đặc biệt đầu tháng.
   - Tần suất: 5 phút sau đầu tháng.  
   - Câu lệnh cron: `5 0 1 * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_firth_day_month_5_second.php`

> **Lưu ý**: Thay `username` và đường dẫn theo cấu trúc hosting của bạn.
