<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_admin")) {
        $data = json_decode($_POST['data'], true);

        // Kiểm tra dữ liệu data phải là kiểu mảng
        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Cập nhật chiết khấu Member
        if (isset($data['updateCardRate_member'])) {
            $member = $purifier->purify($data['updateCardRate_member_rate']);

            if (isEmptyOrNull($member)) {
                response(false, 'Vui lòng nhập chiết khấu Member');
            }

            if (!is_numeric($member)) {
                response(false, 'Chiết khấu Member phải là số');
            }

            // Cập nhật chiết khấu
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'exchange_card_rare_member'", [$member]);
            response(true, 'Cập nhật chiết khấu Member thành công');
        }

        // Cập nhật chiết khấu VIP
        if (isset($data['updateCardRate_vip'])) {
            $vip = $purifier->purify($data['updateCardRate_vip_rate']);

            if (isEmptyOrNull($vip)) {
                response(false, 'Vui lòng nhập chiết khấu VIP');
            }

            if (!is_numeric($vip)) {
                response(false, 'Chiết khấu VIP phải là số');
            }

            // Cập nhật chiết khấu
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'exchange_card_rare_vip'", [$vip]);
            response(true, 'Cập nhật chiết khấu VIP thành công');
        }

        // Cập nhật chiết khấu Đại lý
        if (isset($data['updateCardRate_agency'])) {
            $agency = $purifier->purify($data['updateCardRate_agency_rate']);

            if (isEmptyOrNull($agency)) {
                response(false, 'Vui lòng nhập chiết khấu Đại lý');
            }

            if (!is_numeric($agency)) {
                response(false, 'Chiết khấu Đại lý phải là số');
            }

            // Cập nhật chiết khấu
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'exchange_card_rare_agency'", [$agency]);
            response(true, 'Cập nhật chiết khấu Đại lý thành công');
        }

        // Cập nhật nhà cung cấp ĐỔI THẺ
        if (isset($data['updatePartnerCard'])) {
            $serverName = $purifier->purify($data['updatePartnerCard_serverName']);
            $partnerId  = $purifier->purify($data['updatePartnerCard_partnerId']);
            $partnerKey = $purifier->purify($data['updatePartnerCard_partnerKey']);
            $wallet     = $purifier->purify($data['updatePartnerCard_wallet']);
            $fields = [
                'serverName' => 'tên máy chủ',
                'partnerId' => 'Partner ID',
                'partnerKey' => 'Partner Key',
                'wallet' => 'ví'
            ];

            foreach ($fields as $field => $label) {
                if (isEmptyOrNull($$field)) {
                    response(false, "Vui lòng nhập $label");
                }
            }

            // Cập nhật
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_server_name'", [$serverName]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_id'", [$partnerId]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_key'", [$partnerKey]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'wallet_exchangeCard'", [$wallet]);
            response(true, 'Cập nhật nhà cung cấp thành công');
        }


        // Cập nhật chiết khấu MUA THẺ
        if (isset($data['updateBuyCardRate'])) {
            $viettel        = $purifier->purify($data['updateBuyCardRate_viettel']);
            $vinaphone      = $purifier->purify($data['updateBuyCardRate_vinaphone']);
            $mobifone       = $purifier->purify($data['updateBuyCardRate_mobifone']);
            $vietnamobile   = $purifier->purify($data['updateBuyCardRate_vietnamobile']);
            $garena         = $purifier->purify($data['updateBuyCardRate_garena']);
            $gate           = $purifier->purify($data['updateBuyCardRate_gate']);
            $vcoin          = $purifier->purify($data['updateBuyCardRate_vcoin']);
            $zing           = $purifier->purify($data['updateBuyCardRate_zing']);
            $gmobile        = $purifier->purify($data['updateBuyCardRate_gmobile']);
            $appota         = $purifier->purify($data['updateBuyCardRate_appota']);
            $carot          = $purifier->purify($data['updateBuyCardRate_carot']);
            $funcard        = $purifier->purify($data['updateBuyCardRate_funcard']);
            $scoin          = $purifier->purify($data['updateBuyCardRate_scoin']);
            $gosu           = $purifier->purify($data['updateBuyCardRate_gosu']);
            $sohacoin       = $purifier->purify($data['updateBuyCardRate_sohacoin']);
            $oncash         = $purifier->purify($data['updateBuyCardRate_oncash']);
            $bit            = $purifier->purify($data['updateBuyCardRate_bit']);
            $anpay          = $purifier->purify($data['updateBuyCardRate_anpay']);
            $kul            = $purifier->purify($data['updateBuyCardRate_kul']);
            $vega           = $purifier->purify($data['updateBuyCardRate_vega']);
            $kcong          = $purifier->purify($data['updateBuyCardRate_kcong']);
            $vga            = $purifier->purify($data['updateBuyCardRate_vga']);
            $kis            = $purifier->purify($data['updateBuyCardRate_kis']);

            // Kiểm tra rỗng
            if (isEmptyOrNull($viettel) || isEmptyOrNull($vinaphone) || isEmptyOrNull($mobifone) || isEmptyOrNull($vietnamobile) || isEmptyOrNull($garena) || isEmptyOrNull($gate) || isEmptyOrNull($vcoin) || isEmptyOrNull($zing) || isEmptyOrNull($gmobile) || isEmptyOrNull($appota) || isEmptyOrNull($carot) || isEmptyOrNull($funcard) || isEmptyOrNull($scoin) || isEmptyOrNull($gosu) || isEmptyOrNull($sohacoin) || isEmptyOrNull($oncash) || isEmptyOrNull($bit) || isEmptyOrNull($anpay) || isEmptyOrNull($kul) || isEmptyOrNull($vega) || isEmptyOrNull($kcong) || isEmptyOrNull($vga) || isEmptyOrNull($kis)) {
                response(false, 'Vui lòng nhập đầy đủ thông tin');
            }

            // Kiểm tra số
            if (!is_numeric($viettel) || !is_numeric($vinaphone) || !is_numeric($mobifone) || !is_numeric($vietnamobile) || !is_numeric($garena) || !is_numeric($gate) || !is_numeric($vcoin) || !is_numeric($zing) || !is_numeric($gmobile) || !is_numeric($appota) || !is_numeric($carot) || !is_numeric($funcard) || !is_numeric($scoin) || !is_numeric($gosu) || !is_numeric($sohacoin) || !is_numeric($oncash) || !is_numeric($bit) || !is_numeric($anpay) || !is_numeric($kul) || !is_numeric($vega) || !is_numeric($kcong) || !is_numeric($vga) || !is_numeric($kis)) {
                response(false, 'Vui lòng nhập số');
            }

            // Cập nhật chiết khấu
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_viettel'", [$viettel]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_vina'", [$vinaphone]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_mobi'", [$mobifone]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_vnmobile'", [$vietnamobile]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_garena'", [$garena]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_gate'", [$gate]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_vcoin'", [$vcoin]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_zing'", [$zing]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_gmobile'", [$gmobile]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_appota'", [$appota]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_carot'", [$carot]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_funcard'", [$funcard]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_scoin'", [$scoin]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_gosu'", [$gosu]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_sohacoin'", [$sohacoin]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_oncash'", [$oncash]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_bitvn'", [$bit]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_anpay'", [$anpay]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_kul'", [$kul]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_vega'", [$vega]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_kcong'", [$kcong]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_vga'", [$vga]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'buyCard_rare_kis'", [$kis]);
            response(true, 'Cập nhật chiết khấu thành công');
        }

        // Cập nhật nhà cung cấp MUA THẺ
        if (isset($data['updateBuyCard'])) {
            $serverName = $purifier->purify($data['updateBuyCard_partnerServer']);
            $partnerid  = $purifier->purify($data['updateBuyCard_partnerId']);
            $partnerkey = $purifier->purify($data['updateBuyCard_partnerKey']);
            $wallet     = $purifier->purify($data['updateBuyCard_wallet']);

            if (isEmptyOrNull($serverName)) {
                response(false, 'Vui lòng nhập tên máy chủ');
            }

            if (isEmptyOrNull($partnerid)) {
                response(false, 'Vui lòng nhập Partner ID');
            }

            if (isEmptyOrNull($partnerkey)) {
                response(false, 'Vui lòng nhập Partner Key');
            }

            if (isEmptyOrNull($wallet)) {
                response(false, 'Vui lòng nhập ví');
            }

            // Cập nhật 
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_server_name_buyCard'", [$serverName]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_id_buyCard'", [$partnerid]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_key_buyCard'", [$partnerkey]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'wallet_buyCard'", [$wallet]);
            response(true, 'Cập nhật nhà cung cấp thành công');
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
