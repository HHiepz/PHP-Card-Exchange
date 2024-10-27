<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

// Header
$title_website = 'Top 10 thành viên nạp tháng 02';
require('../../layout/client/header.php');
?>


<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="d-block mb-8 text-center">CHÍNH SÁCH BẢO MẬT</h2>
                        <div class="divider mt-18 mb-16"></div>

                        <div class="block-content">

                            <div class="mt-4 p-5 rounded">
                                <div class="mb-32">
                                    <h4 class="fw-bold mb-16">I. Thu thập thông tin</h4>
                                    <p>Card2k thu thập thông tin của người dùng qua việc:</p>
                                    <ul>
                                        <li>* Đăng ký, đăng nhập và sử dụng dịch vụ tài khoản trên website card2k.com.</li>
                                    </ul>
                                </div>
                                <div class="mb-32">
                                    <h4 class="fw-bold">II. Phạm vi sử dụng thông tin</h4>
                                    <p>Card2k sử dụng thông tin đã thu thập nhầm:</p>
                                    <ul>
                                        <li>* Phục vụ các chức năng của hệ thống.</li>
                                        <li>* Làm căn cứ để bảo vệ người dùng giải quyết tranh chấp, mất cấp hay thất lạc tài khoản.</li>
                                        <li>* Nâng cao chất lượng dịch vụ.</li>
                                    </ul>
                                </div>
                                <div class="mb-32">
                                    <h4 class="fw-bold">III. Đối tượng được tiếp cận thông tin</h4>
                                    <p>Các cá nhân, tổ chức được quyền tiếp cận thông tin người chơi là:</p>
                                    <ul>
                                        <li>* Ban quản trị & nhân viên của Card2k.</li>
                                        <li>* Cơ quan chức năng có thẩm quyền theo quy định của pháp luật.</li>
                                    </ul>
                                </div>
                                <div class="mb-32">
                                    <h4 class="fw-bold">IV. Bảo mật thông tin</h4>
                                    <p>Card2k cam kết bảo mật thông tin người dùng:</p>
                                    <ul>
                                        <li>* Không tiết lộ thông tin người dùng ra ngoài trừ trường hợp có yêu cầu của cơ quan chức năng có thẩm quyền.</li>
                                        <li>* Cam kết không mua bán thông tin của người chơi cho các cá nhân, tổ chức khác.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="text-center mt-32">
                                <p class="text-muted">Card2k có quyền thay đổi chính sách bảo mật mà không cần thông báo trước.</p>
                                <p class="text-muted">Cập nhật lần cuối: 29/03/2024</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer
require('../../layout/client/footer.php');
?>