<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Автопостинг на Facebook");

include_once("Facebook/autoload.php");
include_once("lib/getObject.php");

$app_id = '725004367925770';
$app_secret = '5e654ff8997d4d772531a1ed0c607743';
$access_token = 'EAAKTYzvPkgoBANnGqiYhf4ZAVhv76DYcoBvQmTYIClAPOtF4C4mvS80Y7HiYCKsUWeJyzyMOK7Saugu9ARrxhokGzdZC4dIpwfZCrx7LmW4eZBOAemxdpCsa7LSj1ZBanlUZCDyKvPJnbpRW8j3I5YVRsodpbcqdznOIdY8wP24wZDZD';
$page_id = '111006380257592';

$fb = new Facebook\Facebook(array(
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.2',
));
$fb->setDefaultAccessToken($access_token);


$object = new getObject();
$item = $object->get();

if (!empty($item)) {

    if (!empty($item["photo_gallery"])) {
        foreach ($item["photo_gallery"] as $photo) {
            $request_data["caption"] = $fb->fileToUpload($photo);
            $request_data['published'] = false;
            $batch = [
                'photo' => $fb->request('POST', "/{$page_id}/photos", $request_data)
            ];
            $responses = $fb->sendBatchRequest($batch);
            $data = $responses->getDecodedBody();
            $json_data_responses = json_decode($data[0]["body"], true);
            $publish_photo_id[] = $json_data_responses["id"];
            print_r($json_data_responses);
        }
    }

    foreach ($publish_photo_id as $i => $id) {
        $request_post["attached_media[".$i."]"] = '{"media_fbid":"'.$id.'"}';
    }


    $request_post["message"] = '
    📢 Продаётся  '.$item["name"].'
    
    
    '.$item["text"].'
    
    
    Более подробная информация и контакты владельца в бесплатном приложении:
    🔗ios — https://apps.apple.com/ru/app/iprox/id1456461525
    🔗android — https://l.facebook.com/l.php?u=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dpro.mitapp.morealti%26hl%3Dru%26fbclid%3DIwAR1c_xXoOLf3Dt1oYLqZMVg9E5FTwGRakx9gRiqnek4vv2VLLQstDSifKuU&h=AT1JVZIHzQjhcHhVX_y9JLUYN60RM-tjaD0RDb05EFx4elglirlJLFvgBWvTXU_0g-NRy3RVkV-L4tN4anPTx2XhMVI4_pAYSS9nfKWcU7MRHXdZdgAERsl2bQmyLzO3RFN45Tb_ECypxxY8Ac3bykiITDOHmRuoAGWiM0C53OV9E_QCf1ur0NazV7ll_fFTEhVmZmVU9H_pf84HVXWB29IT15HBbf-NAIkjf7XhPPMz3b3cu-JJ67SrxLj5Ye5vlQ0QKH8B1uvBEsr0DeInhRpiHL3UIv456zijwodVpHr1pxHqAm9nAZazOocG33VNWoqKFByCzCJTLLdqok37SkUncsMCIUjR2KsJIRlr-dJNR0xqDfd38mpEZDcT618beiRVofaSmIzyZgRzMS8R6YgVIv7vYCnYzjUkXBAJRveB0NEaFv7ydGi1K5wXuKhyXeI0u-u0wbjkmq5nEYbdvganGLY9RYDFjjUxquhnquS-_zcN9iZfIxHYZZggYMjvjAn8F_C_LS2kxP3rJJnFmM9RaZ7RpMWc1Zwc5FFJh1sqqUIe0fbeC4AMyRjJ9A1fvMa-V7ytd_qNG-kedhR_PfoCB3ew7UpDS74YWTNvzfcezmA4TIRNm4Qzu_mnOfA
    🔗сайт - https://iprox.ru/sell/flat/'.$item["id"].'/
    ';

    $batch = [
        'photo' => $fb->request('POST', "/{$page_id}/feed", $request_post)
    ];
    $responses = $fb->sendBatchRequest($batch);
    $data = $responses->getDecodedBody();
    if ($data[0]["code"] == "200") {
        echo '<div>Пост успешно размещен</div>';
        $object->setMarker($item["id"]);
    }
    else {
        echo '<div>Произошла ошибка</div>';
        print_r($data);
    }

}
else {
    echo 'Объект не найден';
}



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>