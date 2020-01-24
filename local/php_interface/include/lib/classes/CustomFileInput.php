<? 
use Bitrix\Main\UI;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\FileInputUnclouder;
/**
 *
 * Класс сохранения Изображений в публичке как в админке (на базе  \Bitrix\Main\UI\FileInput)
 * поддерживает сортировку в публиче
 *
 *
 *
 * PHOTO_FILES[n#IND#] загруженные файлы из битры будут иметь структуру PHOTO_FILES[0],PHOTO_FILES[1]
 *  а файлы загружаемые PHOTO_FILES[n0] PHOTO_FILES[n1]
 * 
 * @author sea
 * @example CustomFileInput::createInstance(array("name" =>  'PHOTO_FILES[n#IND#]',"id"=> "PHOTO_FILES[n#IND#]_".mt_rand(1, 1000000),"description" => true,"upload" => true,"allowUpload" => "I",
                     "medialib" => true,
                     "fileDialog" => true,
                     "cloud" => false,
                     "delete" => true,
					 "edit"   => false,
                     "maxCount" => false,
					 "maxSize" => 2*1024*1024,
					 "allowSort" => true,
                  ))->CustomshowWithSort(count($arr_object_images) > 0? $arr_object_images: 0)?
 *
 * 
 */
class CustomFileInput extends \Bitrix\Main\UI\FileInput

{
	public static function createInstance($params = array(), $hashIsID = true)
	{
		$c = __CLASS__;
		return new $c($params, $hashIsID);
	}
	private function getExtDialogs()
	{
		if ($this->uploadSetts["medialib"] && Loader::includeModule("fileman"))
		{
			$this->uploadSetts["medialib"] = array(
					"click" => "OpenMedialibDialog".$this->id,
					"handler" => "SetValueFromMedialib".$this->id
			);
			\CMedialib::ShowDialogScript(array(
					"event" => $this->uploadSetts["medialib"]["click"],
					"arResultDest" => array(
							"FUNCTION_NAME" => $this->uploadSetts["medialib"]["handler"]
					)
			));
		}
		if ($this->uploadSetts["fileDialog"])
		{
			$this->uploadSetts["fileDialog"] = array(
					"click" => "OpenFileDialog".$this->id,
					"handler" => "SetValueFromFileDialog".$this->id
			);
			\CAdminFileDialog::ShowScript
			(
					Array(
							"event" => $this->uploadSetts["fileDialog"]["click"],
							"arResultDest" => array("FUNCTION_NAME" => $this->uploadSetts["fileDialog"]["handler"]),
							"arPath" => array("SITE" => SITE_ID, "PATH" =>"/upload"),
							"select" => 'F',// F - file only, D - folder only
							"operation" => 'O',
							"showUploadTab" => true,
							"allowAllFiles" => true,
							"SaveConfig" => true,
					)
			);
		}
	}
	private function formFile($fileId = "", $inputName = "file")
	{
		$result = array(
				'id' => $fileId,
				'name' => 'Unknown',
				'description_name' => self::getInputName($inputName, "_descr"),
				'description' => '',
				'size' => 0,
				'type' => 'unknown',
				'input_name' => $inputName,
				'input_value' => $fileId,
				'entity' => "file",
				'ext' => ''
		);
		if (!empty($this->elementSetts["properties"]))
		{
			foreach ($this->elementSetts["properties"] as $key)
			{
				$result[$key."_name"] = self::getInputName($inputName, "_".$key);
				$result[$key] = "";
			}
		}
		return $result;
	}
	private function getFile($fileId = "", $inputName = "file")
	{
		$result = NULL;
		$properties = array();
		if (is_array($fileId))
		{
			$properties = $fileId;
			unset($properties["ID"]);
			$fileId = $fileId["ID"];
		}
	
		if (($ar = \CFile::GetFileArray($fileId)) && is_array($ar))
		{
			$name = (strlen($ar['ORIGINAL_NAME'])>0?$ar['ORIGINAL_NAME']:$ar['FILE_NAME']);
			$result = array(
					'id' => $fileId,
					'name' => $name,
					'description_name' => self::getInputName($inputName, "_descr"),
					'description' => str_replace('"', "&quot;", $ar['DESCRIPTION']),
					'size' => $ar['FILE_SIZE'],
					'type' => $ar['CONTENT_TYPE'],
					'input_name' => $inputName,
					'input_value' => $fileId,
					'entity' => (($ar["WIDTH"] > 0 && $ar["HEIGHT"] > 0) ? "image" : "file"),
					'ext' => GetFileExtension($name),
					'real_url' => $ar['SRC']
			);
			if ($result['entity'] == "image")
			{
				$result['tmp_url'] = FileInputUnclouder::getSrc($ar);
				$result['preview_url'] = FileInputUnclouder::getSrcWithResize($ar, array('width' => 200, 'height' => 200));
				$result['width'] = $ar["WIDTH"];
				$result['height'] = $ar["HEIGHT"];
			}
		}
		else
		{
			$strFilePath = $_SERVER["DOCUMENT_ROOT"].$fileId;
			$io = \CBXVirtualIo::GetInstance();
			if($io->FileExists($strFilePath))
			{
				$flTmp = $io->GetFile($strFilePath);
				if ($flTmp->IsExists())
				{
					$ar = \CFile::GetImageSize($strFilePath);
					$result = array(
							'id' => md5($fileId),
							'name' => $flTmp->getName(),
							'description_name' => self::getInputName($inputName, "_descr"),
							'description' => "",
							'size' => $flTmp->GetFileSize(),
							'type' => $flTmp->getType(),
							'input_name' => $inputName,
							'input_value' => $fileId,
							'entity' => ((is_array($ar) && $ar["WIDTH"] > 0 && $ar["HEIGHT"] > 0) ? "image" : "file"),
							'ext' => GetFileExtension($flTmp->getName()),
							'real_url' => $fileId
					);
					if ($result['entity'] == "image")
						$result['tmp_url'] = $fileId;
				}
			}
		}
		if (!empty($this->elementSetts["properties"]))
		{
			foreach ($this->elementSetts["properties"] as $key)
			{
				$result[$key."_name"] = self::getInputName($inputName, "_".$key);
				$result[$key] = $properties[$key];
			}
		}
		return $result;
	}
	
	private static function getInputName($inputName, $type = "")
	{
		if ($type == "")
			return $inputName;
		$p = strpos($inputName, "[");
		return  ($p > 0) ? substr($inputName, 0, $p).$type.substr($inputName, $p) : $inputName.$type;
	}
	public function CustomshowWithSort($values = array())
	{
		$assets = \Bitrix\Main\Page\Asset::getInstance();
		$assets->addJs(SITE_DIR."/bitrix/js/main/dd.js", true);
		\CJSCore::Init(array('fileinput'));
		$files = '';
	
		if (is_array($values))
		{
			foreach($values as $inputName => $fileId)
			{
				if ($fileId > 0)
				{
					$res = $this->getFile($fileId, $inputName);
					$t = $this->templates["uploaded"];
					if (!is_array($res))
					{
						$res = $this->formFile($fileId, $inputName);
						$t = $this->templates["unexisted"];
					}
					$patt = array();
					foreach ($res as $pat => $rep)
						$patt[] = "#".$pat."#";
					$files .= str_ireplace($patt, array_values($res), $t);
					$this->files[] = $res;
				}
			}
		}
		else if (($fileId = intval($values)) > 0)
		{
			$res = $this->getFile($fileId, $this->elementSetts["name"]);
			$t = $this->templates["uploaded"];
			if (!is_array($res))
			{
				$res = $this->formFile($fileId, $this->elementSetts["name"]);
				$t = $this->templates["unexisted"];
			}
			$patt = array();
			foreach ($res as $pat => $rep)
				$patt[] = "#".$pat."#";
			$files .= str_ireplace($patt, array_values($res), $t);
			$this->files[] = $res;
		}
	
		$canDelete = true ? '' : 'adm-fileinput-non-delete'; // In case we can not delete files
		$canEdit = ($this->elementSetts["edit"] ? '' : 'adm-fileinput-non-edit');
	
		//$settings = \CUserOptions::GetOption('main', 'fileinput');
		$settings = (is_array($settings) ? $settings : array(
				"frameFiles" => "N",
				"pinDescription" => "N",
				"mode" => "mode-pict",
				/*"presets" => array(
						array("width" => 200, "height" => 200, "title" => "200x200")
				),
				"presetActive" => 0,*/
				"allowSort" => "Y",
		));
		if ($this->uploadSetts["maxCount"] == 1)
		{
			if ($this->uploadSetts["allowUpload"] == "I")
				$hintMessage = Loc::getMessage("BXU_DNDMessage01");
			else if ($this->uploadSetts["allowUpload"] == "F")
				$hintMessage = Loc::getMessage("BXU_DNDMessage02", array("#ext#" => $this->uploadSetts["allowUploadExt"]));
			else
				$hintMessage = Loc::getMessage("BXU_DNDMessage03");
	
			if ($this->uploadSetts["maxSize"] > 0)
				$hintMessage .= Loc::getMessage("BXU_DNDMessage04", array("#size#" => \CFile::FormatSize($this->uploadSetts["maxSize"])));
		}
		else
		{
			$maxCount = ($this->uploadSetts["maxCount"] > 0 ? GetMessage("BXU_DNDMessage5", array("#maxCount#" => $this->uploadSetts["maxCount"])) : "");
			if ($this->uploadSetts["allowUpload"] == "I")
				$hintMessage = Loc::getMessage("BXU_DNDMessage1", array("#maxCount#" => $maxCount));
			else if ($this->uploadSetts["allowUpload"] == "F")
				$hintMessage = Loc::getMessage("BXU_DNDMessage2", array("#ext#" => $this->uploadSetts["allowUploadExt"], "#maxCount#" => $maxCount));
			else
				$hintMessage = Loc::getMessage("BXU_DNDMessage3", array("#maxCount#" => $maxCount));
			if ($this->uploadSetts["maxSize"] > 0)
				$hintMessage .= Loc::getMessage("BXU_DNDMessage4", array("#size#" => \CFile::FormatSize($this->uploadSetts["maxSize"])));
		}
	
		$this->getExtDialogs();
	
		$uploadSetts = $this->uploadSetts + $settings;
		if (array_key_exists("presets", $settings))
		{
			/*$uploadSetts["presets"] = $settings["presets"];
			$uploadSetts["presetActive"] = $settings["presetActive"];*/
			
		}
		//sortItems
		$uploadSetts["allowSort"] = "Y";
		
		$template = \CUtil::JSEscape($this->templates["new"]);
		$classSingle = (array_key_exists("maxCount", $uploadSetts) && intval($uploadSetts["maxCount"]) == 1 ? "adm-fileinput-wrapper-single" : "");
		$uploadSetts = \CUtil::PhpToJSObject($uploadSetts);
		$elementSetts = \CUtil::PhpToJSObject($this->elementSetts);
		$values = \CUtil::PhpToJSObject($this->files);
		$mes = array(
				"preview" => GetMessage("BXU_Preview"),
				"nonPreview" => GetMessage("BXU_NonPreview")
		);
	
		$settings["modePin"] = ($settings["pinDescription"] == "Y" && $this->elementSetts["description"] ? "mode-with-description" : "");
		$t = <<<HTML
<div class="adm-fileinput-wrapper {$classSingle} ">
<div class="adm-fileinput-btn-panel">
	<span class="adm-btn add-file-popup-btn" id="{$this->id}_add"></span>
	<div class="adm-fileinput-mode {$settings["mode"]}" id="{$this->id}_mode">
		<a href="#" class="mode-pict" id="{$this->id}ThumbModePreview" title="{$mes["preview"]}"></a>
		<a href="#" class="mode-file" id="{$this->id}ThumbModeNonPreview" title="{$mes["nonPreview"]}"></a>
	</div>
</div>
<div id="{$this->id}_block" class="adm-fileinput-area  {$canDelete} {$canEdit} {$settings['mode']} {$settings["modePin"]}">
	<div class="adm-fileinput-area-container" id="{$this->id}_container">{$files}</div>
	<span class="adm-fileinput-drag-area-hint" id="{$this->id}Notice">{$hintMessage}</span>
<script>
(function(BX)
{
	if (BX)
	{
		BX.ready(function(){
			new BX.UI.FileInput('{$this->id}', {$uploadSetts}, {$elementSetts}, {$values}, '{$template}');
		});
	}
})(window["BX"] || top["BX"]);
</script>
</div>
</div>
HTML;
		return $t;
	}
	
	
	
	
	
	/**
	 * По сути дела, сохраняет картикни и отдает массив ID 
	 * 
	 * @param array $input Массив массивов файла(ов)
	 * @param string $module Модуль для сохранения. По умолчанию iblock
	 * 
	 * @return array
	 */
	public static function convertFilesArrayToID(array $input, $module = "iblock")
	{
		if (!$input || count($input) <= 0)
			return array();
		
			
		$return = array();
		foreach ($input as $File)
		{
			if (is_array($File))
			{
				if (strpos($File["tmp_name"], "/BXTEMP-") !== false && strpos($File["tmp_name"], "upload/tmp/") === false)
				{
					$File["tmp_name"] = "/upload/tmp".$File["tmp_name"];
					$newFile = CFile::MakeFileArray($File["tmp_name"]);
					$newFile["name"] = $File["name"];
					$return[] = CFile::SaveFile($newFile,$settings["module"]);
					//$return["ADD"][] = $File;
					unset($newFile);
				}
			}
			else if (is_numeric($File))
			{
				$return[] = $File;
			}
		}
		return $return;
	}
	
	
	
	/**
	 * Подготавливает картинки для сохранения
	 * 
	 * @param array $settings
	 * @return boolean|array[]|string
	 */
	public static function proccessFilesPost(array $settings)
	{
		if (!$settings["post_name"])
			return false;
		
		if (!$settings["module"])
			$settings["module"] = "iblock";
			
		$return = array("ADD" => array(), "DEL" => array());
		$arFiles = $_REQUEST[$settings["post_name"]];
		$arDelFiles = array_keys((array) $_REQUEST[$settings["post_name"]."_del"]);
		if ($arFiles && count($arFiles) > 0)
		{
			foreach ($arFiles as $FileIndex => $File)
			{
				if (!in_array($FileIndex, $arDelFiles))
				{
					if (is_numeric($File))
					{
						$return["ADD"][] = $File;
					}
					else if (is_array($File))
					{
						if (strpos($File["tmp_name"], "/BXTEMP-") !== false && strpos($File["tmp_name"], "upload/tmp/") === false)
						{
							$File["tmp_name"] = "/upload/tmp".$File["tmp_name"];
						}
						$newFile = CFile::MakeFileArray($File["tmp_name"]);
						$newFile["name"] = $File["name"];
						$return["ADD"][] = CFile::SaveFile($newFile,$settings["module"]);
						//$return["ADD"][] = $File;
						unset($newFile);
					}
				}
				else 
				{
					$return["DEL"][] = $File;
				}
			}
		}
		return $return;
	}
	
	public static function post_proccessFilesPostIblock($postResult, $elementID, $iblockID, $propertyCode)
	{
		global $USER;
		if (
			$postResult === false ||
			!\Bitrix\Main\Loader::includeModule("iblock")
		)
			return false;
		
			
		$PropertyRes = \CIBlockElement::GetProperty(
				$iblockID,
				$elementID,
				array("id"	=>	"ASC"),
				array("CODE"	=>	$propertyCode)
		);
		$arPropValues = array();
		while ($arPropValue = $PropertyRes->GetNext())
		{
			$arPropValues[$arPropValue["PROPERTY_VALUE_ID"]] = $arPropValue["VALUE"];
		}
		
		
		$toUpdate = array();
		
		
		
		if ($postResult["DEL"] && count($postResult["DEL"]) > 0)
		{
			foreach ($arPropValues as $VALUE_ID => $Value)
			{
				if ($Value && $VALUE_ID && in_array($Value, $postResult["DEL"]))
				{
					$toUpdate[$VALUE_ID] = array("VALUE" => array("del" => "Y"));
				}
			}
		}
		if ($postResult["ADD"] && count($postResult["ADD"]) > 0)
		{
			foreach ($postResult["ADD"] as $FileID)
			{
				$key_asValueID = array_search($FileID, $arPropValues);
				if ($key_asValueID !== false)
				{
					$toUpdate[$key_asValueID] = array("VALUE" => $FileID);
				}
				else 
				{
					$toUpdate[] = array("VALUE" => $FileID);
				}
			}
		}
		
		if ($toUpdate && count($toUpdate) > 0)
		{
			$ele = new \CIBlockElement;
			$ele->SetPropertyValueCode($elementID, $propertyCode, $toUpdate);
		}
	}
}
?>