<div class="divider mt-18 mb-16"></div>
<footer class="w-100 py-sm-24 px-sm-32 hp-bg-color-black-20 hp-bg-color-dark-90">
    <div class="row g-16">
        <div class="col-12 col-sm-4">
            <p class="hp-badge-text fw-semibold mb-0 text-center text-sm-start hp-text-color-dark-30">
                Bảo mật, SSL: <a href="<?= getDomain() ?>/chinhsachbaomat" target="_blank">Chính sách bảo mật</a>
            </p>
            <div class="d-flex justify-content-center justify-content-sm-start">
                <a href="<?= $dmca_link ?>" title="DMCA.com Protection Status" class="dmca-badge" target="_blank">
                    <img src="https://images.dmca.com/Badges/dmca-badge-w100-5x1-01.png?ID=0f7cadf7-b4e8-4b75-b789-ccd90c2c7df2" alt="DMCA.com Protection Status" class="d-inline-block">
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-4">
            <p class="hp-badge-text fw-semibold mb-16 text-center hp-text-color-dark-30">
                COPYRIGHT ©2023 - <?= date('Y') ?> HHIEPZ, All rights Reserved
            </p>
            <div class="d-flex justify-content-center">
                <span class="d-inline-block" style="padding-right: 5px">Partner: </span>
                <a href="https://technt.net/" class="d-block" style="width: 50px; height: auto;" target="_blank">
                    <img src="<?= getDomain() . "/frontend/app-assets/img/partner/techNT.png" ?>" class="d-inline-block w-100 h-auto" alt="Technt.net">
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-4 text-center text-sm-end">
            <p class="hp-badge-text fw-semibold mb-0 hp-text-color-dark-30">
                Hỗ trợ & báo cáo: <a href="<?= $footer_support ?>" target="_blank">2K9X CARD2K</a>
            </p>
            <a href="<?= $footer_facebook ?>" target="_blank" class="hp-badge-text fw-semibold mb-0 hp-text-color-dark-30">Facebook</a> |
            <a href="<?= $footer_discord ?>" target="_blank" class="hp-badge-text fw-semibold mb-0 hp-text-color-dark-30">Discord</a> |
            <a href="<?= $footer_telegram ?>" target="_blank" class="hp-badge-text fw-semibold mb-0 hp-text-color-dark-30">Telegram</a> |
            <a href="<?= $footer_youtube ?>" target="_blank" class="hp-badge-text fw-semibold mb-0 hp-text-color-dark-30">Youtube</a>
            <p class="text-muted">Version: <?= "v$version" ?></p>
        </div>
    </div>
</footer>
</div>
</main>

<!-- CUSTOM THEME -->
<div class="hp-theme-customizer">
    <div class="hp-theme-customizer-button">
        <div class="hp-theme-customizer-button-bg">
            <svg width="48" height="121" viewBox="0 0 48 121" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M38.6313 21.7613C46.5046 11.6029 47.6987 2.40985 48 0V61H0C1.03187 53.7789 1.67112 44.3597 13.2122 37.7607C22.0261 32.721 32.4115 29.7862 38.6313 21.7613Z" fill="white"></path>
                <path d="M38.6058 99.5632C46.502 109.568 47.6984 118.627 48 121V61H0C1.03532 68.1265 1.67539 77.4295 13.3283 83.9234C22.1048 88.8143 32.3812 91.6764 38.6058 99.5632Z" fill="white"></path>
            </svg>
        </div>

        <div class="hp-theme-customizer-button-icon">
            <span>
                <span></span>
                <span></span>
                <span></span>
            </span>

            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.19 0H5.81C2.17 0 0 2.17 0 5.81V14.18C0 17.83 2.17 20 5.81 20H14.18C17.82 20 19.99 17.83 19.99 14.19V5.81C20 2.17 17.83 0 14.19 0ZM5.67 3.5C5.67 3.09 6.01 2.75 6.42 2.75C6.83 2.75 7.17 3.09 7.17 3.5V7.4C7.17 7.81 6.83 8.15 6.42 8.15C6.01 8.15 5.67 7.81 5.67 7.4V3.5ZM7.52282 14.4313C7.31938 14.5216 7.17 14.7132 7.17 14.9358V16.5C7.17 16.91 6.83 17.25 6.42 17.25C6.01 17.25 5.67 16.91 5.67 16.5V14.9358C5.67 14.7132 5.5206 14.5216 5.31723 14.4311C4.36275 14.0064 3.7 13.058 3.7 11.95C3.7 10.45 4.92 9.22 6.42 9.22C7.92 9.22 9.15 10.44 9.15 11.95C9.15 13.0582 8.47913 14.0066 7.52282 14.4313ZM14.33 16.5C14.33 16.91 13.99 17.25 13.58 17.25C13.17 17.25 12.83 16.91 12.83 16.5V12.6C12.83 12.19 13.17 11.85 13.58 11.85C13.99 11.85 14.33 12.19 14.33 12.6V16.5ZM13.58 10.77C12.08 10.77 10.85 9.55 10.85 8.04C10.85 6.93185 11.5209 5.98342 12.4772 5.55873C12.6806 5.46839 12.83 5.27681 12.83 5.05421V3.5C12.83 3.09 13.17 2.75 13.58 2.75C13.99 2.75 14.33 3.09 14.33 3.5V5.06421C14.33 5.28681 14.4794 5.47835 14.6828 5.56885C15.6372 5.9936 16.3 6.94195 16.3 8.05C16.3 9.55 15.08 10.77 13.58 10.77Z" fill="#38b4fc"></path>
            </svg>
        </div>
    </div>

    <div class="hp-theme-customizer-container bg-black-0 hp-bg-dark-90">
        <div class="hp-theme-customizer-container-header bg-black-10 hp-bg-dark-85 py-16 px-24">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="h5 mb-0 d-block text-black-80 hp-text-color-dark-0">Customise Website</span>
                    <span class="hp-caption fw-medium d-block text-black-60">Thay đổi theo phong cách của bạn</span>
                </div>

                <div>
                    <button type="button" class="btn btn-text hp-bg-dark-85">
                        <i class="fa-solid fa-xmark" style="font-size: 16px"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="hp-theme-customizer-container-body pb-md-96 pb-mb-0 py-24 px-24">
            <div class="row mx-0 hp-theme-customizer-container-body-item">
                <div class="col-12 px-0 mb-16">
                    <span class="d-block hp-caption text-black-60">Giao diện</span>
                    <span class="d-block h5 mb-0 text-black-100 hp-text-color-dark-0">Tối &amp; Sáng</span>
                </div>

                <div class="col-12 px-0">
                    <div class="row g-24">
                        <div class="col-12 col-md-6">
                            <div class="hp-theme-customizer-container-body-item-svg active" data-theme="light">
                                <svg width="244" height="150" viewBox="0 0 244 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="1" width="242" height="148" fill="white"></rect>
                                    <rect width="37" height="138" transform="translate(6 6)" fill="#DFE6E9"></rect>
                                    <path d="M33.7528 18.2807C32.9591 18.059 32.6126 18.0257 32.6126 18.0257C32.4785 18.0036 31.9531 17.9149 31.2377 17.8928C30.6676 17.8041 30.0751 17.6489 29.6839 17.3275C29.2591 16.9728 28.5437 16.3632 28.2642 16.2192C28.2531 16.2081 28.2419 16.2081 28.2307 16.2081C27.1576 15.4433 25.8497 15 24.4413 15C22.8316 15 21.3672 15.5653 20.227 16.5073C19.9029 16.7512 19.5116 17.0837 19.2434 17.3164C18.8521 17.6489 18.2597 17.793 17.6896 17.8817C16.9742 17.9038 16.4488 18.0036 16.3146 18.0147C16.3146 18.0147 16.0017 18.0479 15.2639 18.2474C14.9397 18.3361 14.9062 18.7794 15.208 18.9235C15.208 18.9235 15.2192 18.9235 15.2192 18.9346C15.6998 19.1673 16.1581 19.5442 16.7282 20.4419C17.4101 21.5059 17.5331 22.182 17.969 23.2017C18.6733 26.1277 21.3002 28.3 24.4301 28.3C27.4482 28.3 29.9969 26.2828 30.8129 23.5231C31.3718 22.315 31.4501 21.6167 32.199 20.4419C32.7691 19.5442 33.2274 19.1673 33.7081 18.9346C33.7416 18.9235 33.7751 18.9013 33.8087 18.8902C34.0881 18.7683 34.0546 18.3582 33.7528 18.2807ZM21.8814 23.4012C20.9872 23.1573 20.4283 22.3372 20.4283 22.3372C20.4283 22.3372 21.3896 21.6389 22.2839 21.8827C23.1781 22.1266 23.67 23.2127 23.67 23.2127C23.67 23.2127 22.7757 23.645 21.8814 23.4012ZM27.2023 23.4012C26.308 23.645 25.4138 23.2017 25.4138 23.2017C25.4138 23.2017 25.8944 22.1155 26.7999 21.8717C27.6942 21.6278 28.6555 22.3261 28.6555 22.3261C28.6555 22.3261 28.0966 23.1684 27.2023 23.4012Z" fill="#B2BEC3"></path>
                                    <rect width="191" height="13" transform="translate(47 6)" fill="#DFE6E9"></rect>
                                    <rect width="191" height="122" transform="translate(47 22)" fill="#DFE6E9">
                                    </rect>
                                    <rect x="1" y="1" width="242" height="148" stroke="white" stroke-width="2">
                                    </rect>
                                </svg>

                                <div class="hp-theme-customizer-container-body-item-svg-check">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16.9987" cy="17.0007" r="14.1667" fill="white"></circle>
                                        <path d="M16.9987 2.83398C9.1872 2.83398 2.83203 9.18915 2.83203 17.0007C2.83203 24.8122 9.1872 31.1673 16.9987 31.1673C24.8102 31.1673 31.1654 24.8122 31.1654 17.0007C31.1654 9.18915 24.8102 2.83398 16.9987 2.83398ZM16.9987 28.334C10.7498 28.334 5.66536 23.2496 5.66536 17.0007C5.66536 10.7517 10.7498 5.66732 16.9987 5.66732C23.2476 5.66732 28.332 10.7517 28.332 17.0007C28.332 23.2496 23.2476 28.334 16.9987 28.334Z" fill="#2D3436"></path>
                                        <path d="M14.1674 19.2479L10.9105 15.9966L8.91016 18.0026L14.1702 23.2514L23.6704 13.7512L21.6672 11.748L14.1674 19.2479Z" fill="#2D3436"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="hp-theme-customizer-container-body-item-svg" data-theme="dark">
                                <svg width="244" height="150" viewBox="0 0 244 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="244" height="150" fill="#111314"></rect>
                                    <rect width="37" height="138" transform="translate(6 6)" fill="#2D3436"></rect>
                                    <path d="M33.7528 18.2807C32.9591 18.059 32.6126 18.0257 32.6126 18.0257C32.4785 18.0036 31.9531 17.9149 31.2377 17.8928C30.6676 17.8041 30.0751 17.6489 29.6839 17.3275C29.2591 16.9728 28.5437 16.3633 28.2642 16.2192C28.2531 16.2081 28.2419 16.2081 28.2307 16.2081C27.1576 15.4433 25.8497 15 24.4413 15C22.8316 15 21.3672 15.5653 20.227 16.5073C19.9029 16.7512 19.5116 17.0837 19.2434 17.3164C18.8521 17.6489 18.2597 17.793 17.6896 17.8817C16.9742 17.9038 16.4488 18.0036 16.3146 18.0147C16.3146 18.0147 16.0017 18.0479 15.2639 18.2474C14.9397 18.3361 14.9062 18.7794 15.208 18.9235C15.208 18.9235 15.2192 18.9235 15.2192 18.9346C15.6998 19.1673 16.1581 19.5442 16.7282 20.4419C17.4101 21.5059 17.5331 22.182 17.969 23.2017C18.6733 26.1277 21.3002 28.3 24.4301 28.3C27.4482 28.3 29.9969 26.2828 30.8129 23.5231C31.3718 22.315 31.4501 21.6167 32.199 20.4419C32.7691 19.5442 33.2274 19.1673 33.7081 18.9346C33.7416 18.9235 33.7751 18.9013 33.8087 18.8902C34.0881 18.7683 34.0546 18.3582 33.7528 18.2807ZM21.8814 23.4012C20.9872 23.1573 20.4283 22.3372 20.4283 22.3372C20.4283 22.3372 21.3896 21.6389 22.2839 21.8827C23.1781 22.1266 23.67 23.2127 23.67 23.2127C23.67 23.2127 22.7757 23.645 21.8814 23.4012ZM27.2023 23.4012C26.308 23.645 25.4138 23.2017 25.4138 23.2017C25.4138 23.2017 25.8944 22.1155 26.7999 21.8717C27.6942 21.6278 28.6555 22.3261 28.6555 22.3261C28.6555 22.3261 28.0966 23.1684 27.2023 23.4012Z" fill="#F7FAFC"></path>
                                    <rect width="191" height="13" transform="translate(47 6)" fill="#2D3436"></rect>
                                    <rect width="191" height="122" transform="translate(47 22)" fill="#2D3436">
                                    </rect>
                                    <rect width="244" height="150" stroke="#DFE6E9"></rect>
                                </svg>

                                <div class="hp-theme-customizer-container-body-item-svg-check">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16.9987" cy="17.0007" r="14.1667" fill="white"></circle>
                                        <path d="M16.9987 2.83398C9.1872 2.83398 2.83203 9.18915 2.83203 17.0007C2.83203 24.8122 9.1872 31.1673 16.9987 31.1673C24.8102 31.1673 31.1654 24.8122 31.1654 17.0007C31.1654 9.18915 24.8102 2.83398 16.9987 2.83398ZM16.9987 28.334C10.7498 28.334 5.66536 23.2496 5.66536 17.0007C5.66536 10.7517 10.7498 5.66732 16.9987 5.66732C23.2476 5.66732 28.332 10.7517 28.332 17.0007C28.332 23.2496 23.2476 28.334 16.9987 28.334Z" fill="#2D3436"></path>
                                        <path d="M14.1674 19.2479L10.9105 15.9966L8.91016 18.0026L14.1702 23.2514L23.6704 13.7512L21.6672 11.748L14.1674 19.2479Z" fill="#2D3436"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-0 hp-theme-customizer-container-body-item">
                <div class="col-12 mb-16 px-0">
                    <span class="d-block hp-caption text-black-60">Độ rộng nội dung</span>
                    <span class="d-block h5 mb-0 text-black-100 hp-text-color-dark-0">Full Width &amp; Boxed</span>
                </div>

                <div class="col-12 px-0">
                    <div class="row g-24">
                        <div class="col-12 col-md-6">
                            <div class="hp-theme-customizer-container-body-item-svg active" data-content="full">
                                <svg width="244" height="150" viewBox="0 0 244 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="1" width="242" height="148" fill="white"></rect>
                                    <rect width="37" height="138" transform="translate(6 6)" fill="#DFE6E9"></rect>
                                    <path d="M33.7528 18.2807C32.9591 18.059 32.6126 18.0257 32.6126 18.0257C32.4785 18.0036 31.9531 17.9149 31.2377 17.8928C30.6676 17.8041 30.0751 17.6489 29.6839 17.3275C29.2591 16.9728 28.5437 16.3632 28.2642 16.2192C28.2531 16.2081 28.2419 16.2081 28.2307 16.2081C27.1576 15.4433 25.8497 15 24.4413 15C22.8316 15 21.3672 15.5653 20.227 16.5073C19.9029 16.7512 19.5116 17.0837 19.2434 17.3164C18.8521 17.6489 18.2597 17.793 17.6896 17.8817C16.9742 17.9038 16.4488 18.0036 16.3146 18.0147C16.3146 18.0147 16.0017 18.0479 15.2639 18.2474C14.9397 18.3361 14.9062 18.7794 15.208 18.9235C15.208 18.9235 15.2192 18.9235 15.2192 18.9346C15.6998 19.1673 16.1581 19.5442 16.7282 20.4419C17.4101 21.5059 17.5331 22.182 17.969 23.2017C18.6733 26.1277 21.3002 28.3 24.4301 28.3C27.4482 28.3 29.9969 26.2828 30.8129 23.5231C31.3718 22.315 31.4501 21.6167 32.199 20.4419C32.7691 19.5442 33.2274 19.1673 33.7081 18.9346C33.7416 18.9235 33.7751 18.9013 33.8087 18.8902C34.0881 18.7683 34.0546 18.3582 33.7528 18.2807ZM21.8814 23.4012C20.9872 23.1573 20.4283 22.3372 20.4283 22.3372C20.4283 22.3372 21.3896 21.6389 22.2839 21.8827C23.1781 22.1266 23.67 23.2127 23.67 23.2127C23.67 23.2127 22.7757 23.645 21.8814 23.4012ZM27.2023 23.4012C26.308 23.645 25.4138 23.2017 25.4138 23.2017C25.4138 23.2017 25.8944 22.1155 26.7999 21.8717C27.6942 21.6278 28.6555 22.3261 28.6555 22.3261C28.6555 22.3261 28.0966 23.1684 27.2023 23.4012Z" fill="#B2BEC3"></path>
                                    <rect width="191" height="13" transform="translate(47 6)" fill="#DFE6E9"></rect>
                                    <rect width="191" height="122" transform="translate(47 22)" fill="#DFE6E9">
                                    </rect>
                                    <rect x="1" y="1" width="242" height="148" stroke="white" stroke-width="2">
                                    </rect>
                                </svg>

                                <div class="hp-theme-customizer-container-body-item-svg-check">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16.9987" cy="17.0007" r="14.1667" fill="white"></circle>
                                        <path d="M16.9987 2.83398C9.1872 2.83398 2.83203 9.18915 2.83203 17.0007C2.83203 24.8122 9.1872 31.1673 16.9987 31.1673C24.8102 31.1673 31.1654 24.8122 31.1654 17.0007C31.1654 9.18915 24.8102 2.83398 16.9987 2.83398ZM16.9987 28.334C10.7498 28.334 5.66536 23.2496 5.66536 17.0007C5.66536 10.7517 10.7498 5.66732 16.9987 5.66732C23.2476 5.66732 28.332 10.7517 28.332 17.0007C28.332 23.2496 23.2476 28.334 16.9987 28.334Z" fill="#2D3436"></path>
                                        <path d="M14.1674 19.2479L10.9105 15.9966L8.91016 18.0026L14.1702 23.2514L23.6704 13.7512L21.6672 11.748L14.1674 19.2479Z" fill="#2D3436"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="hp-theme-customizer-container-body-item-svg" data-content="boxed">
                                <svg width="244" height="150" viewBox="0 0 244 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="244" height="150" fill="white"></rect>
                                    <rect width="37" height="138" transform="translate(6 6)" fill="#DFE6E9"></rect>
                                    <path d="M33.7528 18.2807C32.9591 18.059 32.6126 18.0257 32.6126 18.0257C32.4785 18.0036 31.9531 17.9149 31.2377 17.8928C30.6676 17.8041 30.0751 17.6489 29.6839 17.3275C29.2591 16.9728 28.5437 16.3633 28.2642 16.2192C28.2531 16.2081 28.2419 16.2081 28.2307 16.2081C27.1576 15.4433 25.8497 15 24.4413 15C22.8316 15 21.3672 15.5653 20.227 16.5073C19.9029 16.7512 19.5116 17.0837 19.2434 17.3164C18.8521 17.6489 18.2597 17.793 17.6896 17.8817C16.9742 17.9038 16.4488 18.0036 16.3146 18.0147C16.3146 18.0147 16.0017 18.0479 15.2639 18.2474C14.9397 18.3361 14.9062 18.7794 15.208 18.9235C15.208 18.9235 15.2192 18.9235 15.2192 18.9346C15.6998 19.1673 16.1581 19.5442 16.7282 20.4419C17.4101 21.5059 17.5331 22.182 17.969 23.2017C18.6733 26.1277 21.3002 28.3 24.4301 28.3C27.4482 28.3 29.9969 26.2828 30.8129 23.5231C31.3718 22.315 31.4501 21.6167 32.199 20.4419C32.7691 19.5442 33.2274 19.1673 33.7081 18.9346C33.7416 18.9235 33.7751 18.9013 33.8087 18.8902C34.0881 18.7683 34.0546 18.3582 33.7528 18.2807ZM21.8814 23.4012C20.9872 23.1573 20.4283 22.3372 20.4283 22.3372C20.4283 22.3372 21.3896 21.6389 22.2839 21.8827C23.1781 22.1266 23.67 23.2127 23.67 23.2127C23.67 23.2127 22.7757 23.645 21.8814 23.4012ZM27.2023 23.4012C26.308 23.645 25.4138 23.2017 25.4138 23.2017C25.4138 23.2017 25.8944 22.1155 26.7999 21.8717C27.6942 21.6278 28.6555 22.3261 28.6555 22.3261C28.6555 22.3261 28.0966 23.1684 27.2023 23.4012Z" fill="#B2BEC3"></path>
                                    <g clip-path="url(#clip0_5050:104461)">
                                        <rect width="133" height="13" transform="translate(76 6)" fill="#DFE6E9">
                                        </rect>
                                    </g>
                                    <rect width="133" height="122" transform="translate(76 22)" fill="#DFE6E9">
                                    </rect>
                                    <rect width="244" height="150" stroke="#DFE6E9"></rect>
                                    <defs>
                                        <clipPath id="clip0_5050:104461">
                                            <rect width="133" height="13" fill="white" transform="translate(76 6)">
                                            </rect>
                                        </clipPath>
                                    </defs>
                                </svg>

                                <div class="hp-theme-customizer-container-body-item-svg-check">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16.9987" cy="17.0007" r="14.1667" fill="white"></circle>
                                        <path d="M16.9987 2.83398C9.1872 2.83398 2.83203 9.18915 2.83203 17.0007C2.83203 24.8122 9.1872 31.1673 16.9987 31.1673C24.8102 31.1673 31.1654 24.8122 31.1654 17.0007C31.1654 9.18915 24.8102 2.83398 16.9987 2.83398ZM16.9987 28.334C10.7498 28.334 5.66536 23.2496 5.66536 17.0007C5.66536 10.7517 10.7498 5.66732 16.9987 5.66732C23.2476 5.66732 28.332 10.7517 28.332 17.0007C28.332 23.2496 23.2476 28.334 16.9987 28.334Z" fill="#2D3436"></path>
                                        <path d="M14.1674 19.2479L10.9105 15.9966L8.91016 18.0026L14.1702 23.2514L23.6704 13.7512L21.6672 11.748L14.1674 19.2479Z" fill="#2D3436"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="scroll-to-top">
    <button type="button" class="btn btn-primary btn-icon-only rounded-circle hp-primary-shadow">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path d="M13 7.828V20h-2V7.828l-5.364 5.364-1.414-1.414L12 4l7.778 7.778-1.414 1.414L13 7.828z">
                </path>
            </g>
        </svg>
    </button>
</div>

<!-- Plugin -->
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/jquery.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/bootstrap.bundle.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/swiper-bundle.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/jquery.mask.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/autocomplete.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/moment.min.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/dataTables.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/dataTables.bootstrap5.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/plugin/sweetalert2@10.js"></script>

<!-- Layouts -->
<script src="<?= getDomain() ?>/frontend/app-assets/js/layouts/header-search.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/layouts/sider.js"></script>
<script src="<?= getDomain() ?>/frontend/app-assets/js/components/input-number.js"></script>

<!-- Base -->
<script src="<?= getDomain() ?>/frontend/app-assets/js/base/index.js"></script>
<!-- Customizer -->
<script src="<?= getDomain() ?>/frontend/app-assets/js/customizer.js"></script>

<!-- Horizontal -->
<script src="<?= getDomain() ?>/frontend/app-assets/js/layouts/horizontal-menu.js"></script>

<!-- Custom -->
<script src="<?= getDomain() ?>/frontend/assets/js/main.js?v1.1.0" defer></script>
</body>

</html>