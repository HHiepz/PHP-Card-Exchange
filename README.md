
# Website đổi thẻ cào

* Website liên kết với website nhà mạng thông qua API gữi thẻ cào, mua thẻ cào và thanh toán tự động.


## Tác giả

- [@HHiepz](https://www.github.com/hhiepz)

## 🛠 Skills
Dự án này được xây dựng bằng các công nghệ sau:

* **PHP** - Xử lý logic backend và các yêu cầu của người dùng.
* **MySQL** - Quản lý cơ sở dữ liệu để lưu trữ và truy xuất thông tin thẻ.
* **HTML5 & CSS3** - Tạo dựng giao diện người dùng trực quan và thân thiện.
* **Bootstrap 5** - Tối ưu giao diện người dùng và đảm bảo tính đáp ứng trên nhiều thiết bị.
* **JavaScript & Ajax** - Tương tác động mà không cần tải lại trang.
* **cURL** - Gửi thông tin thẻ đến trang web chính.
## ⏱️ Tự động hóa quy trình

Để tối ưu các quy trình tự động của website, cấu hình các cron jobs sau để đảm bảo các tác vụ được thực hiện đúng lịch trình:

### Các Cron Jobs Cần Thiết

| File                             | Mô tả                                    | Tần suất         | Câu lệnh thực thi                                           |
|----------------------------------|------------------------------------------|------------------|-------------------------------------------------------------|
| `cron/auto_run_1_second.php`     | Xử lý các tác vụ cần chạy liên tục       | Mỗi phút         | `/usr/bin/php -q /home/username/public_html/cron/auto_run_1_second.php` |
| `cron/auto_run_5_second.php`     | Xử lý tác vụ cần mỗi 5 giây              | Mỗi 5 phút         | `/usr/bin/php -q /home/username/public_html/cron/auto_run_5_second.php` |
| `cron/auto_run_1_hour.php`       | Xử lý các tác vụ theo giờ               | Mỗi giờ          | `/usr/bin/php -q /home/username/public_html/cron/auto_run_1_hour.php` |
| `cron/auto_run_firth_day_month.php` | Chạy vào ngày đầu tháng             | Ngày đầu tháng   | `/usr/bin/php -q /home/username/public_html/cron/auto_run_firth_day_month.php` |
| `cron/auto_run_firth_day_month_5_second.php` | Xử lý tác vụ đặc biệt đầu tháng | 5 phút sau đầu tháng   | `/usr/bin/php -q /home/username/public_html/cron/auto_run_firth_day_month_5_second.php` |
| `cron/auto_run_morning.php`      | Chạy vào buổi sáng                       | Hằng ngày vào 00:00 | `/usr/bin/php -q /home/username/public_html/cron/auto_run_morning.php` |


> **Lưu ý**: Thay `username` và đường dẫn theo cấu trúc hosting của bạn.

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