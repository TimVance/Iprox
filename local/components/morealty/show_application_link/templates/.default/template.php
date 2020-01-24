<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?
if(!empty($arResult["os"]) && empty($_COOKIE["download-app"])) { ?>
    <div class="download-app">
        <div class="closed">x</div>
        <div class="container">
            <div class="flexBetween">
                <? if ($arResult["os"] == "ios") { ?>
                    <div><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="app-store-ios" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-app-store-ios fa-w-14 fa-2x"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM127 384.5c-5.5 9.6-17.8 12.8-27.3 7.3-9.6-5.5-12.8-17.8-7.3-27.3l14.3-24.7c16.1-4.9 29.3-1.1 39.6 11.4L127 384.5zm138.9-53.9H84c-11 0-20-9-20-20s9-20 20-20h51l65.4-113.2-20.5-35.4c-5.5-9.6-2.2-21.8 7.3-27.3 9.6-5.5 21.8-2.2 27.3 7.3l8.9 15.4 8.9-15.4c5.5-9.6 17.8-12.8 27.3-7.3 9.6 5.5 12.8 17.8 7.3 27.3l-85.8 148.6h62.1c20.2 0 31.5 23.7 22.7 40zm98.1 0h-29l19.6 33.9c5.5 9.6 2.2 21.8-7.3 27.3-9.6 5.5-21.8 2.2-27.3-7.3-32.9-56.9-57.5-99.7-74-128.1-16.7-29-4.8-58 7.1-67.8 13.1 22.7 32.7 56.7 58.9 102h52c11 0 20 9 20 20 0 11.1-9 20-20 20z" class=""></path></svg></div>
                    <div><a target="_blank" rel="nofollow" href="https://apps.apple.com/ru/app/iprox/id1456461525">Скачайте наше приложение</a></div>
                <? } ?>
                <? if ($arResult["os"] == "android") { ?>
                    <div><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google-play" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-google-play fa-w-16 fa-2x"><path fill="currentColor" d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z" class=""></path></svg></div>
                    <div><a target="_blank" rel="nofollow" href="https://play.google.com/store/apps/details?id=pro.mitapp.morealti">Скачайте наше приложение</a></div>
                <? } ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".download-app .closed").click(function() {
                $(".download-app").hide();
                $.cookie("download-app", 1, { expires : 10 });
            });
        });
    </script>
<?}?>
