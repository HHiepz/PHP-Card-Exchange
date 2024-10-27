## ⏱️ Tự động hóa quy trình (khi dùng trên hosting)

Để tối ưu các quy trình tự động của website, cấu hình các cron jobs sau để đảm bảo các tác vụ được thực hiện đúng lịch trình:

### Cấu hình các Cron Jobs

1. **auto_run_1_second.php**  
   - Mô tả: Xử lý các tác vụ chạy liên tục.
   - Tần suất: Mỗi phút.  
   - Câu lệnh cron: `* * * * *`  
   - Lệnh: `/usr/bin/php -q /home/username/public_html/cron/auto_run_1_second.php`

2. **auto_run_5_second.php**  
   - Mô tả: Xử lý tác vụ mỗi 5 giây.
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
