<?

header('Content-Type: application/rss+xml; charset=utf-8');

echo '<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <title>Iprox Каталог</title>
    <link>https://iprox.ru/</link>
    <description>Каталог iprox</description>
    <language>en-us</language>
    <pubDate>'.date(DATE_RSS, time()).'</pubDate>
    <lastBuildDate>'.date(DATE_RSS, time()).'</lastBuildDate>
    <docs>https://iprox.ru/rss/</docs>
    <generator>Iprox</generator>';


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT", "TIMESTAMP_X", "DETAIL_PAGE_URL");
$arFilter =     Array(
    "IBLOCK_ID" => "7",
    "ACTIVE" => "Y",
    "=PROPERTY_IS_ACCEPTED" => "417",
    "=PROPERTY_watermark_avito_delete" => "1",
);
$res = CIBlockElement::GetList(Array("ID" => "DESC"), $arFilter, false, Array("nTopCount" => 1000, "iNumPage" =>1), $arSelect);
$propertyes_value = [];



$k = 0;
while ($ob = $res->GetNextElement()) {
	//if ($k > 10) break;
	$k++;
	$item = $ob->GetFields();
	$arProp = $ob->GetProperty("photo_gallery");
	$propertyes_value[$arProp["CODE"]] = $arProp["VALUE"];
	echo '<item>
			<title>'.$item["NAME"].'</title>
			<link>'.'https://'.$_SERVER["SERVER_NAME"].$item["DETAIL_PAGE_URL"].'</link>
			<guid>'.'https://'.$_SERVER["SERVER_NAME"].$item["DETAIL_PAGE_URL"].'</guid>
			<description><![CDATA['.strip_tags(addslashes($item["DETAIL_TEXT"]));

	foreach ($propertyes_value["photo_gallery"] as $photo) {
		if (!empty($photo)) {
			echo '<img src="'.CFile::GetPath($photo).'"></picture>';
		}
	}

	echo ']]></description>
			<pubDate>'.date(DATE_RSS, strtotime($item["TIMESTAMP_X"])).'</pubDate>';
	echo '
		</item>
	';
}
echo '
  </channel>
</rss>
';

//echo $k;

?>