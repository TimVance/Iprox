<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: CMLExportYandexMarketCustom.php                                  #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
class CMLExportYandexMarketCustom
{
    public function GetMLExportServiceID()
    {
        return 'YANDEX_MARKET';
    }

    private function GetNameLimit()
    {
        return 150;
    }

    private function GetDescLimit()
    {
        return 1000;
    }

    public function Start( $arPrm )
    {
        $content = '<?xml version="1.0" encoding="'.$arPrm['FILE_ENCODE'].'"?>'."\n";
        $content .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'."\n";
        $content .= '<yml_catalog date="'.date('Y-m-d H:i', time()).'">'."\n";
        $content .= '	<shop>'."\n";

        return $content;
    }

    public function Header( $arPrm )
    {
        if( isset($arPrm['SITE_META']['BROWSER_TITLE']) && !empty($arPrm['SITE_META']['BROWSER_TITLE']) ):
            $title = $arPrm['SITE_META']['BROWSER_TITLE'];
        elseif( isset($arPrm['SITE_META']['TITLE']) && !empty($arPrm['SITE_META']['TITLE']) ):
            $title = $arPrm['SITE_META']['TITLE'];
        else:
            $title = $arPrm['SITE']['SITE_NAME'];
        endif;

        $content = '		<name>'.$title.'</name>'."\n";
        $content .= '		<company>'.$Company.'</company>'."\n";
        $content .= '		<url>http://'.$arPrm['SITE']['SERVER_NAME'].'/</url>'."\n";
        $content .= '		<platform>1C-Bitrix</platform>'."\n";
        $content .= '		<version>'.SM_VERSION.'</version>'."\n";
        $content .= '		<agency>KREATTIKA</agency>'."\n";
        $content .= '		<email>info@kreattika.ru</email>'."\n";

        if( is_array($arPrm['CURRENCIES']) && count($arPrm['CURRENCIES']) > 0 ):
            $content .= '		<currencies>'."\n";
            foreach( $arPrm['CURRENCIES'] as $arCurrency ):
                $content .= '			<currency id="'.$arCurrency['CODE'].'" rate="'.$arCurrency['RATE'].'"';
                if( isset($arCurrency['PLUS']) && !empty($arCurrency['PLUS']) ):
                    $content .= ' plus="'.$arCurrency['PLUS'].'"';
                endif;
                $content .= '/>'."\n";
            endforeach;
            $content .= '		</currencies>'."\n";
        endif;

        if( is_array($arPrm['SECTIONS']) && count($arPrm['SECTIONS']) > 0 ):
            $content .= '		<categories>'."\n";
            foreach( $arPrm['SECTIONS'] as $arSect ):
                $content .= '			<category id="'.$arSect['ID'].'"';
                if( isset($arSect['PARENT_ID']) && !empty($arSect['PARENT_ID']) ):
                    $content .= ' parentId="'.$arSect['PARENT_ID'].'"';
                endif;
                $sectName = CMLExport::text2xml($arSect['NAME'], array('CutTextLenght'=>self::GetNameLimit()));
                $content .= '>'.$sectName.'</category>'."\n";
            endforeach;
            $content .= '		</categories>'."\n";
        endif;

        $content .= '		<offers>'."\n";
        return $content;
    }

    public function Item( $arPrm )
    {

        if( isset($arPrm['ITEM']['DETAIL_TEXT']) && !empty($arPrm['ITEM']['DETAIL_TEXT']) ):
            if( $arPrm['ITEM']['DETAIL_TEXT_TYPE'] == 'html' ):
                $isHTML = true;
            else:
                $isHTML = false;
            endif;
            $description = CMLExport::text2xml($arPrm['ITEM']['~DETAIL_TEXT'], array('CutTextLenght'=>self::GetDescLimit()));
        elseif( isset($arPrm['ITEM']['PREVIEW_TEXT']) && !empty($arPrm['ITEM']['PREVIEW_TEXT']) ):
            if( $arPrm['ITEM']['PREVIEW_TEXT_TYPE'] == 'html' ):
                $isHTML = true;
            else:
                $isHTML = false;
            endif;
            $description = CMLExport::text2xml($arPrm['ITEM']['~PREVIEW_TEXT'], array('CutTextLenght'=>self::GetDescLimit()));
        else:
            $description = '';
        endif;

        $arImg = array();
        if( isset($arPrm['ITEM']['PREVIEW_PICTURE']) && !empty($arPrm['ITEM']['PREVIEW_PICTURE']) && intval($arPrm['ITEM']['PREVIEW_PICTURE']) > 0 ):
            $arImg[] = CFile::GetPath($arPrm['ITEM']['PREVIEW_PICTURE']);
        endif;
        if( isset($arPrm['ITEM']['DETAIL_PICTURE']) && !empty($arPrm['ITEM']['DETAIL_PICTURE']) && intval($arPrm['ITEM']['DETAIL_PICTURE']) > 0 ):
            $arImg[] = CFile::GetPath($arPrm['ITEM']['DETAIL_PICTURE']);
        endif;

        $content = '			<offer id="'.$arPrm['ITEM']['ID'].'" available="true">'."\n";
        $content .= '				<url>http://'.$arPrm['SITE']['SERVER_NAME'].$arPrm['ITEM']['DETAIL_PAGE_URL'].'</url>'."\n";
        $content .= '				<price>'.$arPrm['ITEM']['PRICE']['VALUE'].'</price>'."\n";
        $content .= '				<currencyId>'.$arPrm['ITEM']['PRICE']['CURRENCY'].'</currencyId>'."\n";
        $content .= '				<categoryId>'.$arPrm['ITEM']['IBLOCK_SECTION_ID'].'</categoryId>'."\n";
        $content .= '				<store>true</store>'."\n";
        $content .= '				<pickup>true</pickup>'."\n";
        $content .= '				<delivery>true</delivery>'."\n";

        if( count($arImg) > 0 ):
            foreach($arImg as $keyImg=>$valueImg):
                if( $keyImg == 0 ):
                    $content .= '				<picture>http://'.$arPrm['SITE']['SERVER_NAME'].$valueImg.'</picture>'."\n";
                    breack;
                endif;
            endforeach;
        endif;

        $name = CMLExport::text2xml($arPrm['ITEM']['~NAME'], array('CutTextLenght'=>self::GetNameLimit()));
        $content .= '				<name>'.$name.'</name>'."\n";

        if( !empty($description) ):
            $content .= '				<description>'.$description.'</description>'."\n";
        endif;

        /*
        $content .= '
                <adult>true</adult>';

        $content .= '
                <age unit="year">16</age>';

        $content .= '
                <cpa></cpa>';

        $content .= '
                <param></param>';
*/

        $content .= '			</offer>'."\n";

        return $content;
    }

    public function End()
    {
        $content = '		</offers>'."\n";
        $content .= '	</shop>'."\n";
        $content .= '</yml_catalog>';

        return $content;
    }
}
?>