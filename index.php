<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Интернет-магазин \"Одежда\"");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\ScalarFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\LogglyHandler;
//use Monolog\Handler\RollbarHandler;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;
use Rollbar\Monolog\Handler\RollbarHandler;
?>
<?
$loggerBitrix = \Monolog\Registry::getInstance('debug');
$loggerBitrix->info('Debug session', array(
    'item_id' => 0,
    'Data' => $_SESSION
));

$loggerApp = \Monolog\Registry::getInstance('app');
$loggerApp->info('info text example');

$loggerMonolog = new Logger('logger');
$loggerMonolog->pushHandler(new StreamHandler($_SERVER["DOCUMENT_ROOT"] . '/loggerToFile.log', Logger::INFO));
$loggerMonolog->pushHandler(new BrowserConsoleHandler());


$handlerInfoStream = new RotatingFileHandler($_SERVER["DOCUMENT_ROOT"] . '/logs/info.log', 0, Logger::INFO);
$handlerWarningStream = new RotatingFileHandler($_SERVER["DOCUMENT_ROOT"] . '/logs/warning.log', 0, Logger::WARNING);
$handlerErrorStream = new RotatingFileHandler($_SERVER["DOCUMENT_ROOT"] . '/logs/error.log', 0, Logger::ERROR);

$handlerInfoStream->setFilenameFormat('{date}-{filename}', 'Y/m/d');
$handlerWarningStream->setFilenameFormat('{date}-{filename}', 'Y/m/d');
$handlerErrorStream->setFilenameFormat('{date}-{filename}', 'Y/m/d');

$loggerMonolog->pushHandler($handlerInfoStream);
$loggerMonolog->pushHandler($handlerWarningStream);
$loggerMonolog->pushHandler($handlerErrorStream);

$handlerMail = new NativeMailerHandler('root@localhost.com', "monolog", COption::GetOptionString('main', 'email_from'), Logger::INFO);
$handlerMail->setFormatter(new HtmlFormatter());
$handlerMail->setContentType('text/html');
$loggerMonolog->pushHandler($handlerMail);

$handlerSlack = new SlackWebhookHandler("https://hooks.slack.com/services/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",
    null, null, true, "sunglasses", false, false, Logger::INFO);

$loggerToSlack = new Logger('loggerToSlack');
$loggerMonolog->pushHandler($handlerSlack);
$loggerToSlack->pushHandler($handlerSlack);

$loggerMonolog->pushHandler(new LogglyHandler('70997a00-a48f-4e33-9059-812d67a5f383/tag/monolog', Logger::INFO));

Rollbar::init(
    array(
        'access_token' => '5a7e9c4897f341e3abc1be8e292f55c0',
        'environment' => 'development'
    )
);
$loggerMonolog->pushHandler(new RollbarHandler(Rollbar::logger(), Logger::INFO));



$loggerMonolog->addWarning('test logs');
$loggerMonolog->warning('warning text example');
$loggerMonolog->error('error text example');
$loggerMonolog->error('error text', array('session' => $_SESSION));
$loggerMonolog->info('info text example');
$loggerMonolog->info('info text', array('session' => $_SESSION));
$loggerMonolog->debug('debug text', array('session' => $_SESSION));
$loggerMonolog->info(json_encode(array('session' => $_SESSION)));
$loggerMonolog->addInfo('new message', array('session' => $_SESSION));

$loggerToSlack->warning('warning text example to slack ');//<!everyone>
$loggerToSlack->info(json_encode(array('session' => $_SESSION)));




$dateFormat = "Y n j, g:i a";
$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
$formatter = new LineFormatter($output, $dateFormat);
$streamFormatted = new StreamHandler($_SERVER["DOCUMENT_ROOT"] . '/formattedLog.log', Logger::DEBUG);
$streamFormatted->setFormatter($formatter);
$loggerFormatted = new Logger('loggerFormatted');
$loggerFormatted->pushHandler($streamFormatted);

$loggerFormatted->warning('warning text example');
$loggerFormatted->error('error text example');
$loggerFormatted->error('error text', array('session' => $_SESSION));
$loggerFormatted->info('info text example');
$loggerFormatted->info('info text', array('session' => $_SESSION));




?>

<?if (IsModuleInstalled("advertising")):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner",
	"bootstrap",
	array(
		"COMPONENT_TEMPLATE" => "bootstrap",
		"TYPE" => "MAIN",
		"NOINDEX" => "Y",
		"QUANTITY" => "3",
		"BS_EFFECT" => "fade",
		"BS_CYCLING" => "N",
		"BS_WRAP" => "Y",
		"BS_PAUSE" => "Y",
		"BS_KEYBOARD" => "Y",
		"BS_ARROW_NAV" => "Y",
		"BS_BULLET_NAV" => "Y",
		"BS_HIDE_FOR_TABLETS" => "N",
		"BS_HIDE_FOR_PHONES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
	),
	false
);?>
<?endif?>

<h2>Тренды сезона</h2>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	".default",
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "2",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>