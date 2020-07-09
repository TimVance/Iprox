<?

use Bitrix\Main\ModuleManager;
use Bitrix\Main\UI\Extension;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

class UIToolbarComponent extends CBitrixComponent
{
	/**
	 * Execute component.
	 *
	 * @return void
	 */

	public function executeComponent()
	{
		\Bitrix\Main\Loader::includeModule('ui');

		$GLOBALS["APPLICATION"]->addBufferContent([$this, "includeTemplate"]);

		Extension::load(["ui.buttons", "ui.buttons.icons"]);
		$this->initComponentTemplate();
		$GLOBALS["APPLICATION"]->setAdditionalCSS($this->getTemplate()->getFolder()."/style.css");
		$GLOBALS["APPLICATION"]->addHeadScript($this->getTemplate()->getFolder()."/script.js");
	}

	public function includeTemplate()
	{
		//it's a dirty hack to prevent showing a white screen when some php error happens.
		if ($this->shouldPreventOutputBuffering())
		{
			return "";
		}

		ob_start();

		$pageTitle = $GLOBALS["APPLICATION"]->getViewContent("pagetitle");
		$insidePageTitle = $GLOBALS["APPLICATION"]->getViewContent("inside_pagetitle");

		$isBitrix24Cloud = ModuleManager::isModuleInstalled("bitrix24");
		$oldToolbar = $GLOBALS["INTRANET_TOOLBAR"];
		$oldToolbarButtons = !$isBitrix24Cloud && $oldToolbar->isEnabled() && count($oldToolbar->getButtons()) > 0;

		if (strlen($pageTitle) || strlen($insidePageTitle) || $oldToolbarButtons)
		{
			$this->includeComponentTemplate("old");
		}
		else
		{
			$this->includeComponentTemplate();
		}

		return ob_get_clean();
	}

	private function shouldPreventOutputBuffering()
	{
		if (defined("BX_BUFFER_SHUTDOWN"))
		{
			return true;
		}

		$trace = \Bitrix\Main\Diag\Helper::getBackTrace(0, DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($trace as $traceLine)
		{
			if (
				isset($traceLine['function']) &&
				in_array($traceLine['function'], ['ob_end_flush', 'ob_end_clean', 'LocalRedirect'])
			)
			{
				return true;
			}
		}

		return false;
	}
}