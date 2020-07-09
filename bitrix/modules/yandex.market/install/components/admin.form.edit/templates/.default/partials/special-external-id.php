<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) { die(); }

use Yandex\Market;
use Bitrix\Main;

Main\Localization\Loc::loadMessages(__FILE__);

/** @var $component Yandex\Market\Components\AdminFormEdit */
/** @var $specialFields array */

foreach ($specialFields as $specialFieldKey)
{
	$field = $component->getField($specialFieldKey);

	if ($field)
	{
		?>
		<tr>
			<td width="40%" align="right" valign="top">
				<?
				include __DIR__ . '/field-title.php';
				?>
			</td>
			<td width="60%">
				<?
				echo $component->getFieldHtml($field);

				if (!empty($field['DESCRIPTION']))
				{
				    echo BeginNote();
				    echo $field['DESCRIPTION'];
				    echo EndNote();
				}
				?>
			</td>
		</tr>
		<?
	}
}