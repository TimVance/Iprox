var esolIXModuleName = 'esol.importxml';
var esolIXModuleFilePrefix = 'esol_import_xml';
var EIXPreview = {
	Init: function()
	{
		eval('var params = ' + $('#esol_ix_xml_wrap input[name="settings_json"]').val() + ';');
		eval('var extraparams = ' + $('#esol_ix_xml_wrap input[name="extrasettings_json"]').val() + ';');
		$('#esol_ix_xml_wrap input[name="settings_json"]').remove();
		$('#esol_ix_xml_wrap input[name="extrasettings_json"]').remove();
		$('#esol_ix_xml_wrap input[name="defaultsettings_json"]').remove();

		if(params.GROUPS)
		{
			for(var i in params.GROUPS)
			{
				this.currentTag = this.GetTagByXPath(params.GROUPS[i]);
				if(this.currentTag)
				{
					this.SetBaseElement(i);
				}				
			}
		}
		if(params.FIELDS)
		{
			var val, xpath, arVals, valObj, option;
			var selectNames = ['section_fields', 'subsection_fields', 'element_fields', 'property_fields'];
			for(var j=0; j<selectNames.length; j++)
			{
				var select = $('#esol_ix_xml_wrap select[name="'+selectNames[j]+'"]');
				for(var i in params.FIELDS)
				{
					arVals = params.FIELDS[i].split(';');
					xpath = arVals[0];
					val = arVals[1];
					valObj = this.GetValObjByXPath(xpath);
					if(valObj != false)
					{
						option = select.find('option[value="'+val+'"]');
						if(option.length > 0) this.SetFieldValue(valObj, option[0], i, (typeof extraparams=='object' ? extraparams[i] : ''));
					}
				}
			}
		}
	},
	
	ShowBaseElements: function(link)
	{
		this.currentTag = $(link).closest('.esol_ix_xml_struct_item');
		var menuItems = [];
		menuItems.push({
			TEXT: BX.message("ESOL_IX_GROUP_ELEMENT"),
			ONCLICK: 'EIXPreview.SetBaseElement("ELEMENT")'
		});
		BX.adminShowMenu(link, menuItems, {active_class: "bx-adm-scale-menu-butt-active"});
	},
	
	SetBaseElement: function(type)
	{
		if(type)
		{
			var obj = this;
			this.UnsetBaseElement(type);
			
			$(this.currentTag).closest('.esol_ix_xml_struct_item').attr('data-base-element', type.toLowerCase());
			$(this.currentTag).find('> .esol_ix_group_value').html('<input type="hidden" name="SETTINGS[GROUPS]['+type+']" value=""><span>'+BX.message("ESOL_IX_GROUP_"+type)+'<a href="javascript:void(0)" onclick="return EIXPreview.UnsetBaseElement(\''+type+'\')" class="esol_ix_group_value_close" title="'+BX.message("ESOL_IX_REMOVE_FIELD")+'"></a></span>');
			$('#esol_ix_xml_wrap input[name="SETTINGS[GROUPS]['+type+']"]').val(this.GetXPathByTag(this.currentTag));
			var objValue = $(this.currentTag).find('.esol_ix_str_value');
			objValue.addClass('esol_ix_str_value_active');
			objValue.find('.esol_ix_str_value_val').bind('click', function(){obj.ShowElementFields(this);});
			$(this.currentTag).find('.esol_ix_str_value[data-attr]').bind('contextmenu', function(e){return obj.ShowAttrActions(e, this);});
		}
	},
	
	UnsetBaseElement: function(type)
	{
		if(type)
		{
			var oldInput = $('#esol_ix_xml_wrap input[name="SETTINGS[GROUPS]['+type+']"]');
			if(oldInput.length > 0)
			{
				oldInput.closest('.esol_ix_xml_struct_item').removeAttr('data-base-element');
				var objValue = oldInput.closest('.esol_ix_xml_struct_item').find('.esol_ix_str_value');
				objValue.find('.esol_ix_str_value_field .esol_ix_str_value_close').trigger('click');
				objValue.removeClass('esol_ix_str_value_active');
				objValue.find('.esol_ix_str_value_val').unbind('click').unbind('contextmenu');
				oldInput.closest('.esol_ix_group_value').html('');
			}
		}
	},
	
	GetXPathByTag: function(tag)
	{
		if($(tag).attr('data-attr')) var xpath = '@'+$(tag).attr('data-attr');
		else var xpath = $(tag).attr('data-name');
		while((tag = $(tag).parent()) && tag.hasClass('esol_ix_xml_struct_item'))
		{
			xpath = $(tag).attr('data-name') + '/'+ xpath;
		}
		return xpath;
	},
	
	GetXPathByVal: function(valObj)
	{
		var xpath = this.GetXPathByTag(valObj.closest('.esol_ix_xml_struct_item'));
		if(valObj.attr('data-attr'))
		{
			xpath += '/@'+valObj.attr('data-attr');
		}
		return xpath;
	},
	
	GetTagByXPath: function(xpath)
	{
		var arPath = xpath.split('/');
		var parent = $('#esol_ix_xml_wrap .esol_ix_xml_struct');
		var i = 0;
		while(i < arPath.length && (parent = parent.find('> .esol_ix_xml_struct_item[data-name="'+arPath[i]+'"]')) && parent.length > 0){i++;}
		if(i < arPath.length) return false;
		return parent;
	},
	
	GetValObjByXPath: function(xpath)
	{
		var attr = '';
		var arPath = xpath.split('/');
		if(arPath[arPath.length - 1].substr(0, 1)=='@')
		{
			attr = arPath.pop().substr(1);
			xpath = arPath.join('/');
		}
		var tag = this.GetTagByXPath(xpath);
		if(tag==false) return false;
		
		var valObj = tag.find('> .esol_ix_str_value' + (attr.length > 0 ? '[data-attr="'+attr+'"]' : ':not([data-attr])'));
		return valObj;
	},
	
	ShowElementFields: function(valObj, event)
	{
		var obj = this;
		valObj = $(valObj);
		var copySettings = ((typeof event == 'object') && (event.ctrlKey || event.shiftKey));
		var fieldsCode = valObj.closest('.esol_ix_xml_struct_item[data-base-element]').attr('data-base-element');
		var pSelect = $('#esol_ix_xml_wrap select[name="'+fieldsCode+'_fields"]');
		var select = $(pSelect).clone();
		var options = select[0].options;
		var oldValue = this.GetFieldValue(valObj);
		for(var i=0; i<options.length; i++)
		{
			if(oldValue==options.item(i).value) options.item(i).selected = true;
		}
		
		var chosenId = 'esolix_select_chosen';
		$('#'+chosenId).remove();
		var offset = valObj.offset();
		var div = $('<div></div>');
		div.attr('id', chosenId);
		div.css({
			position: 'absolute',
			left: offset.left,
			top: offset.top
		});
		div.append(select);
		$('body').append(div);
		
		select.chosen({search_contains: true});
		select.bind('change', function(){
			var option = options.item(select[0].selectedIndex);
			var settings = false;
			if(copySettings)
			{
				settings = valObj.prev('.esol_ix_str_value_field').find('.esol_ix_str_value_settings input').val();
				if(settings.length > 0) eval('settings = '+settings+';');
			}
			if(typeof settings == 'object')
			{
				obj.SetFieldValue(valObj, option, false, settings);
			}
			else
			{
				obj.SetFieldValue(valObj, option);
			}
			select.chosen('destroy');
			$('#'+chosenId).remove();
		});
		
		$('body').one('click', function(e){
			e.stopPropagation();
			return false;
		});
		var chosenDiv = select.next('.chosen-container')[0];
		$('a:eq(0)', chosenDiv).trigger('mousedown');
		
		var lastClassName = chosenDiv.className;
		var interval = setInterval( function() {   
			   var className = chosenDiv.className;
				if (className !== lastClassName) {
					select.trigger('change');
					lastClassName = className;
					clearInterval(interval);
				}
			},30);
	},
	
	ShowAttrActions: function(e, valObj)
	{
		return;
		this.currentAttr = $(valObj);
		var linkObj = $(valObj).prev('.esol_ix_str_value_cm');
		if(linkObj.length == 0)
		{
			$(valObj).before('<a href="javascript:void(0)" class="esol_ix_str_value_cm"></a>');
			linkObj = $(valObj).prev('.esol_ix_str_value_cm');
			
			var menuItems = [];
			menuItems.push({
				TEXT: BX.message("ESOL_IX_SHOW_ALL_ATTRIBUTES"),
				ONCLICK: 'EIXPreview.SetGroupTags()'
			});
			BX.adminShowMenu(linkObj[0], menuItems, {active_class: "bx-adm-scale-menu-butt-active"});
		}
		else
		{
			BX.fireEvent(linkObj[0], 'click');
		}
		return false;
	},
	
	SetGroupTags: function()
	{
		var xpath = this.GetXPathByTag(this.currentAttr);
		var post = $(this.currentAttr).closest('form').serialize() + '&ACTION=GET_GROUP_TAGS';
		$.post(window.location.href, post, function(data){
			alert(data);
		});
	},
	
	GetFieldValue: function(valObj)
	{
		var input = valObj.find('input[name^="SETTINGS[FIELDS]["]');
		if(input.length > 0)
		{
			var arVals = input.val().split(';');
			if(arVals.length==2) return arVals[1];
		}
		return '';
	},
	
	SetFieldValue: function(valObj, option, num, extraparams)
	{
		valObj = $(valObj);
		var valObjParent = valObj.closest('.esol_ix_str_value');
		if((typeof option == 'object') && option.value)
		{
			var textValue = '';
			var optgroup = $(option).closest('optgroup');
			if(optgroup.length > 0)
			{
				textValue = optgroup.attr('label');
				if(textValue.length > 0) textValue += ' - ';
			}
			textValue += option.text;
			var xpath = this.GetXPathByVal(valObjParent);
			
			if(valObj.hasClass('esol_ix_str_value_field'))
			{
				var span = valObj;
				if(!num && num!==0)
				{
					var input = $('input[name^="SETTINGS[FIELDS]["]', span);
					if(input.length > 0)
					{
						num = input.attr('name').replace(/^.*\[(\d+)\]$/, '$1');
					}
				}
			}
			else
			{
				var obj = this;
				var valObjVal = valObjParent.find('.esol_ix_str_value_val');
				if(!valObjVal.hasClass('esol_ix_str_value_val_selected'))
				{
					valObjVal.addClass('esol_ix_str_value_val_selected').unbind('click');
					//valObjParent.append('<a href="javascript:void(0)" onclick="return EIXPreview.ShowElementFields(this, event)" class="esol_ix_str_value_add" title="'+BX.message("ESOL_IX_ADD_FIELD")+'"></a>');
					var addLink = $('<a href="javascript:void(0)" class="esol_ix_str_value_add" title="'+BX.message("ESOL_IX_ADD_FIELD")+'\r\n\r\n'+BX.message("ESOL_IX_ADD_FIELD_COPY_SETTING")+'"></a>');
					addLink.bind('click', function(e){return EIXPreview.ShowElementFields(this, e)});
					valObjParent.append(addLink);
				}
				if(!num && num!==0)
				{
					var inputs = $('#esol_ix_xml_wrap input[name^="SETTINGS[FIELDS]["]');
					var i = 0;
					while($('#esol_ix_xml_wrap input[name="SETTINGS[FIELDS]['+i+']"]').length > 0)
					{
						i++;
					}
					num = i;
				}
				var span = $('<span class="esol_ix_str_value_field"><input type="hidden" name="SETTINGS[FIELDS]['+num+']" value=""><span></span><a href="javascript:void(0)" onclick="return EIXPreview.ShowFieldSettings(event, this)" class="esol_ix_str_value_settings" id="field_settings_'+num+'" title="'+BX.message("ESOL_IX_FIELD_SETTINGS")+'"><input name="EXTRASETTINGS['+num+']" value="" type="hidden"></a><a href="javascript:void(0)" onclick="return EIXPreview.DeleteFieldValue(event, this)" class="esol_ix_str_value_close" title="'+BX.message("ESOL_IX_REMOVE_FIELD")+'"></a></span>');
				span.insertBefore(valObjParent.find('.esol_ix_str_value_add'));
				//valObjParent.append(span);
				
				if(typeof extraparams=='object')
				{
					span.find('.esol_ix_str_value_settings input').val(JSON.stringify(extraparams));
				}
				else
				{
					span.find('.esol_ix_str_value_settings').addClass("inactive");
				}
				span.bind('click', function(){obj.ShowElementFields(this);});
			}
			
			if(option.value=='VARIABLE') textValue += ' {'+num+'}';
			span.find('span').html(textValue);
			span.find('input[name^="SETTINGS[FIELDS]["]').val(xpath+';'+option.value);
		}
		else
		{
			if(valObj.hasClass('esol_ix_str_value_field'))
			{
				valObj.find('a.esol_ix_str_value_close').trigger('click');
			}
		}
	},
	
	DeleteFieldValue: function(e, link)
	{
		e.stopPropagation();
		var parent = $(link).closest('.esol_ix_str_value');
		$(link).closest('.esol_ix_str_value_field').remove();
		if(parent.find('.esol_ix_str_value_field').length==0)
		{
			var obj = this;
			parent.find('.esol_ix_str_value_val').removeClass('esol_ix_str_value_val_selected').bind('click', function(){obj.ShowElementFields(this);});
			parent.find('.esol_ix_str_value_add').remove();
		}
		return false;
	},
	
	ShowBaseElements2: function(link)
	{
		var pSelect = $('#esol_ix_xml_wrap select[name="group"]');
		var select = $(pSelect).clone();
		var options = select[0].options;
		/*for(var i=0; i<options.length; i++)
		{
			if(inputVal.value==options.item(i).value) options.item(i).selected = true;
		}*/
		
		var chosenId = 'esolix_select_chosen';
		$('#'+chosenId).remove();
		var offset = $(link).offset();
		var div = $('<div></div>');
		div.attr('id', chosenId);
		div.css({
			position: 'absolute',
			left: offset.left,
			top: offset.top,
			width: 300
		});
		div.append(select);
		$('body').append(div);
		
		select.chosen();
		select.bind('change', function(){
			var option = options.item(select[0].selectedIndex);
			/*if(option.value)
			{
				input.value = option.text;
				input.title = option.text;
				inputVal.value = option.value;
			}
			else
			{
				input.value = '';
				input.title = '';
				inputVal.value = '';
			}*/
			select.chosen('destroy');
			$('#'+chosenId).remove();
		});
		
		$('body').one('click', function(e){
			e.stopPropagation();
			return false;
		});
		var chosenDiv = select.next('.chosen-container')[0];
		$('a:eq(0)', chosenDiv).trigger('mousedown');
		
		var lastClassName = chosenDiv.className;
		var interval = setInterval( function() {   
			   var className = chosenDiv.className;
				if (className !== lastClassName) {
					select.trigger('change');
					lastClassName = className;
					clearInterval(interval);
				}
			},30);
	},
	
	ShowFieldSettings: function(e, btn)
	{
		e.stopPropagation();
		
		var parent = $(btn).closest('.esol_ix_str_value_field');
		var title = parent.find('>span:eq(0)').html().replace(/\s+\{\d+\}$/, '');
		var val = this.GetFieldValue(parent);
		var name = $(btn).find('input[type=hidden]').attr('name');
		var index = name.replace(/^.*\[([^\]]*)\]$/, '$1');
		
		var form = $(btn).closest('form')[0];
		var postextra = $('input', btn).val();
		var poststruct = $('#esol_ix_xml_wrap input[name="struct_base64"]').val();
		var fieldsCode = $(btn).closest('.esol_ix_xml_struct_item[data-base-element]').attr('data-base-element');
		var xPathList = {};
		var groups = $('#esol_ix_xml_wrap input[name^="SETTINGS[GROUPS]["]');
		for(var i=0; i<groups.length; i++)
		{
			var groupCode = groups[i].name.replace(/^.*\[([^\[]*)\]$/, '$1');
			xPathList[groupCode] = groups[i].value;
		}
		
		var dialogParams = {
			'title':BX.message("ESOL_IX_POPUP_FIELD_SETTINGS_TITLE") + ' "' + title + '" {'+index+'}',
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_field_settings_hl.php?field='+val+'&field_name='+name+'&index='+index+'&PROFILE_ID='+form.PROFILE_ID.value,
			'width':'900',
			'height':'400',
			'resizable':true,
			'content_post':{'POSTEXTRA': postextra, 'POSTSTRUCT': poststruct, 'XPATH_LIST': xPathList, 'GROUP': fieldsCode.toUpperCase()}
		};
		var dialog = new BX.CAdminDialog(dialogParams);
			
		dialog.SetButtons([
			dialog.btnCancel,
			new BX.CWindowButton(
			{
				title: BX.message('JS_CORE_WINDOW_SAVE'),
				id: 'savebtn',
				name: 'savebtn',
				className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
				action: function () {
					this.disableUntilError();
					this.parentWindow.PostParameters();
					//this.parentWindow.Close();
				}
			})
		]);
			
		BX.addCustomEvent(dialog, 'onWindowRegister', function(){
			$('input[type=checkbox]', this.DIV).each(function(){
				BX.adminFormTools.modifyCheckbox(this);
			});
			ESettings.BindConversionEvents();
		});
			
		dialog.Show();
		
		return false;
	},
	
	SetExtraParams: function(oid, returnJson)
	{
		if(typeof returnJson == 'object') returnJson = JSON.stringify(returnJson);
		if(returnJson.length > 0) $('#'+oid).removeClass("inactive");
		else $('#'+oid).addClass("inactive");
		$('#'+oid+' input').val(returnJson);
		if(BX.WindowManager.Get()) BX.WindowManager.Get().Close();
	}
}

var EProfile = {
	Init: function()
	{
		var select = $('select#PROFILE_ID');
		if(select.length > 0)
		{
			if(select.val().length > 0)
			{
				$.post(window.location.href, {'MODE': 'AJAX', 'ACTION': 'DELETE_TMP_DIRS'}, function(data){});
			}
			
			select = select[0]
			/*this.Choose(select[0]);*/
			if(select.value=='new')
			{
				$('#new_profile_name').css('display', '');
			}
			else
			{
				$('#new_profile_name').css('display', 'none');
			}
		
			$('select.adm-detail-iblock-list').bind('change', function(){
				$.post(window.location.href, {'MODE': 'AJAX', 'HIGHLOADBLOCK_ID': this.value, 'ACTION': 'GET_UID'}, function(data){
					var fields = $(data).find('select[name="fields[]"]');
					var select = $('select[name="SETTINGS_DEFAULT[ELEMENT_UID][]"]');
					fields.val(select.val());
					fields.attr('name', select.attr('name'));
					//$('select.chosen').chosen('destroy');
					select.replaceWith(fields);
					//$('select.chosen').chosen();
				});
			});
			
			var select = $('select[name="SETTINGS_DEFAULT[ELEMENT_UID][]"]');
			//if(select.length > 0 && !select.val()) select[0].options[0].selected = true;
			/*$('select.chosen').chosen();*/
			$('select.kda-chosen-multi').chosen({width: '300px'});
			this.ToggleAdditionalSettings();
			
			$('#dataload input[type="checkbox"][data-confirm]').bind('change', function(){
				if(this.checked && !confirm(this.getAttribute('data-confirm')))
				{
					this.checked = false;
				}
			});
		}
	},
	
	Choose: function(select)
	{
		/*if(select.value=='new')
		{
			$('#new_profile_name').css('display', '');
		}
		else
		{
			$('#new_profile_name').css('display', 'none');
		}*/
		var id = (typeof select == 'object' ? select.value : select);
		var query = window.location.search.replace(/PROFILE_ID=[^&]*&?/, '');
		if(query.length < 2) query = '?';
		if(query.length > 1 && query.substr(query.length-1)!='&') query += '&';
		query += 'PROFILE_ID=' + id;
		window.location.href = query;
	},
	
	Delete: function()
	{
		var obj = this;
		var select = $('select#PROFILE_ID');
		var option = select[0].options[select[0].selectedIndex];
		var id = option.value;
		$.post(window.location.href, {'MODE': 'AJAX', 'ID': id, 'ACTION': 'DELETE_PROFILE'}, function(data){
			obj.Choose('');
		});
	},
	
	Copy: function()
	{
		var obj = this;
		var select = $('select#PROFILE_ID');
		var option = select[0].options[select[0].selectedIndex];
		var id = option.value;
		$.post(window.location.href, {'MODE': 'AJAX', 'ID': id, 'ACTION': 'COPY_PROFILE'}, function(data){
			eval('var res = '+data+';');
			obj.Choose(res.id);
		});
	},
	
	ShowRename: function()
	{
		var select = $('select#PROFILE_ID');
		var option = select[0].options[select[0].selectedIndex];
		var name = option.innerHTML;
		
		var tr = $('#new_profile_name');
		var input = $('input[type=text]', tr);
		input.val(name);
		if(!input.attr('init_btn'))
		{
			input.after('&nbsp;<input type="button" onclick="EProfile.Rename();" value="OK">');
			input.attr('init_btn', 1);
		}
		tr.css('display', '');
	},
	
	Rename: function()
	{
		var select = $('select#PROFILE_ID');
		var option = select[0].options[select[0].selectedIndex];
		var id = option.value;
		
		var tr = $('#new_profile_name');
		var input = $('input[type=text]', tr);
		var value = $.trim(input.val());
		if(value.length==0) return false;
		
		tr.css('display', 'none');
		option.innerHTML = value;
		
		$.post(window.location.href, {'MODE': 'AJAX', 'ID': id, 'NAME': value, 'ACTION': 'RENAME_PROFILE'}, function(data){});
	},
	
	ShowCron: function()
	{
		var dialog = new BX.CAdminDialog({
			'title':BX.message("ESOL_IX_POPUP_CRON_TITLE"),
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_cron_settings.php?suffix=highload',
			'width':'800',
			'height':'350',
			'resizable':true});
			
		dialog.SetButtons([
			dialog.btnCancel/*,
			new BX.CWindowButton(
			{
				title: BX.message('JS_CORE_WINDOW_SAVE'),
				id: 'savebtn',
				name: 'savebtn',
				className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
				action: function () {
					this.disableUntilError();
					this.parentWindow.PostParameters();
					//this.parentWindow.Close();
				}
			})*/
		]);
			
		dialog.Show();
	},
	
	SaveCron: function(btn)
	{
		var form = $(btn).closest('form');
		$.post(form[0].getAttribute('action'), form.serialize()+'&subaction='+btn.name, function(data){
			$('#esol-ix-cron-result').html(data);
		});
	},
	
	RemoveProccess: function(link, id)
	{
		var post = {
			'MODE': 'AJAX',
			'PROCCESS_PROFILE_ID': id,
			'ACTION': 'REMOVE_PROCESS_PROFILE'
		};
		
		$.ajax({
			type: "POST",
			url: window.location.href,
			data: post,
			success: function(data){
				var parent = $(link).closest('.kda-proccess-item');
				if(parent.parent().find('.kda-proccess-item').length <= 1)
				{
					parent.closest('.adm-info-message-wrap').hide();
				}
				parent.remove();
			}
		});
	},
	
	ContinueProccess: function(link, id)
	{
		var parent = $(link).closest('div');
		parent.append('<form method="post" action="" style="display: none;">'+
						'<input type="hidden" name="PROFILE_ID" value="'+id+'">'+
						'<input type="hidden" name="STEP" value="3">'+
						'<input type="hidden" name="PROCESS_CONTINUE" value="Y">'+
						'<input type="hidden" name="sessid" value="'+$('#sessid').val()+'">'+
					  '</form>');
		parent.find('form')[0].submit();
	},
	
	ToggleAdditionalSettings: function(link)
	{
		if(link) link = $(link);
		else link = $('.esol_ix_head_more');
		if(link.length==0) return;
		$(link).each(function(){
			var tr = $(this).closest('tr');
			var show = $(this).hasClass('show');
			while((tr = tr.next('tr:not(.heading)')) && tr.length > 0)
			{
				if(show) tr.hide();
				else tr.show();
			}
			if(show) $(this).removeClass('show');
			else $(this).addClass('show');
		});
	},
	
	RadioChb: function(chb1, chb2name, confirmMessage)
	{
		if(chb1.checked)
		{
			if(!confirmMessage || confirm(confirmMessage))
			{
				var form = $(chb1).closest('form');
				if(typeof chb2name=='object')
				{
					for(var i=0; i<chb2name.length; i++)
					{
						if(form[0][chb2name[i]]) form[0][chb2name[i]].checked = false;
					}
				}
				if(form[0][chb2name]) form[0][chb2name].checked = false;
			}
			else
			{
				chb1.checked = false;
			}
		}
	},
	
	OpenMissignElementFields: function(link)
	{
		var form = $(link).closest('form');
		var iblockId = $('select[name="SETTINGS_DEFAULT[IBLOCK_ID]"]', form).val();
		var input = $(link).prev('input[type=hidden]');
		
		var dialogParams = {
			'title':BX.message(input.attr('id').indexOf('OFFER_')==0 ? "ESOL_IX_POPUP_MISSINGOFFER_FIELDS_TITLE" : "ESOL_IX_POPUP_MISSINGELEM_FIELDS_TITLE"),
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_missignelem_fields.php?IBLOCK_ID='+iblockId+'&INPUT_ID='+input.attr('id')+'&OLDDEFAULTS='+input.val(),
			'width':'800',
			'height':'400',
			'resizable':true
		};
		var dialog = new BX.CAdminDialog(dialogParams);
			
		dialog.SetButtons([
			dialog.btnCancel,
			new BX.CWindowButton(
			{
				title: BX.message('JS_CORE_WINDOW_SAVE'),
				id: 'savebtn',
				name: 'savebtn',
				className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
				action: function () {
					this.disableUntilError();
					this.parentWindow.PostParameters();
					//this.parentWindow.Close();
				}
			})
		]);
			
		BX.addCustomEvent(dialog, 'onWindowRegister', function(){
			$('input[type=checkbox]', this.DIV).each(function(){
				BX.adminFormTools.modifyCheckbox(this);
			});
			$('select.esol-ix-chosen-multi').chosen();
		});
			
		dialog.Show();
		
		return false;
	},
	
	ShowEmailForm: function()
	{
		var pid = $('#PROFILE_ID').val();
		var post = '';
		var json = $('.esol-ix-file-choose input[name="SETTINGS_DEFAULT[EMAIL_DATA_FILE]"]').val();
		if(json)
		{
			eval('post = {EMAIL_SETTINGS: '+json+'};');
		}
		var dialog = new BX.CAdminDialog({
			'title':BX.message("ESOL_IX_POPUP_SOURCE_EMAIL"),
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_source_email.php?PROFILE_ID='+pid,
			'content_post': post,
			'width':'900',
			'height':'450',
			'resizable':true});
			
		BX.addCustomEvent(dialog, 'onWindowRegister', function(){
			
		});
		
		dialog.SetButtons([
			dialog.btnCancel,
			new BX.CWindowButton(
			{
				title: BX.message('JS_CORE_WINDOW_SAVE'),
				id: 'savebtn',
				name: 'savebtn',
				className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
				action: function () {
					this.disableUntilError();
					this.parentWindow.PostParameters();
					//this.parentWindow.Close();
				}
			})
		]);
			
		dialog.Show();
	},
	
	CheckEmailConnectData: function(link)
	{
		var form = $(link).closest('form');
		var post = form.serialize()+'&action=checkconnect';
		$.ajax({
			type: "POST",
			url: form.attr('action'),
			data: post,
			success: function(data){
				eval('var res = '+data+';');
				if(res.result=='success') $('#connect_result').html('<div class="success">'+BX.message("ESOL_IX_SOURCE_EMAIL_SUCCESS")+'</div>');
				else $('#connect_result').html('<div class="fail">'+BX.message("ESOL_IX_SOURCE_EMAIL_FAIL")+'</div>');
				
				if(res.folders)
				{
					var select = $('select[name="EMAIL_SETTINGS[FOLDER]"]', form);
					var oldVal = select.val();
					$('option', select).remove();
					for(var i in res.folders)
					{
						var option = $('<option>'+res.folders[i]+'</option>');
						option.attr('value', i);
						select.append(option);
					}
					select.val(oldVal);
				}
			},
			error: function(){
				$('#connect_result').html('<div class="fail">'+BX.message("ESOL_IX_SOURCE_EMAIL_FAIL")+'</div>');
			},
			timeout: 5000
		});
	},
	
	ShowFileAuthForm: function()
	{
		var pid = $('#PROFILE_ID').val();
		var post = '';
		var json = $('.esol-ix-file-choose input[name="EXT_DATA_FILE"]').val();
		if(json && json.substr(0,1)=='{')
		{
			eval('post = {AUTH_SETTINGS: '+json+'};');
		}
		var dialog = new BX.CAdminDialog({
			'title':BX.message("ESOL_IX_POPUP_SOURCE_LINKAUTH"),
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_source_linkauth.php?PROFILE_ID='+pid,
			'content_post': post,
			'width':'900',
			'height':'450',
			'resizable':true});
			
		BX.addCustomEvent(dialog, 'onWindowRegister', function(){
			
		});
		
		dialog.SetButtons([
			dialog.btnCancel,
			new BX.CWindowButton(
			{
				title: BX.message('JS_CORE_WINDOW_SAVE'),
				id: 'savebtn',
				name: 'savebtn',
				className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
				action: function () {
					this.disableUntilError();
					this.parentWindow.PostParameters();
					//this.parentWindow.Close();
				}
			})
		]);
			
		dialog.Show();
	},
	
	SetLinkAuthParams: function(jData)
	{
		if($('.esol-ix-file-choose input[name="EXT_DATA_FILE"]').length == 0)
		{
			$(".esol-ix-file-choose").prepend('<input type="hidden" name="EXT_DATA_FILE" value="">');
		}
		$('.esol-ix-file-choose input[name="EXT_DATA_FILE"]').val(JSON.stringify(jData));
		$('.esol-ix-file-choose input[name="SETTINGS_DEFAULT[EMAIL_DATA_FILE]"]').val('');
		BX.WindowManager.Get().Close();
	},
	
	LauthAddVar: function(link)
	{
		var tr = $(link).closest('tr').prev('tr.esol-ix-lauth-var');
		var newTr = tr.clone();
		newTr.find('input').val('');
		tr.after(newTr);
	},
	
	CheckLauthConnectData: function(link)
	{
		var form = $(link).closest('form');
		var post = form.serialize()+'&action=checkconnect';
		$.ajax({
			type: "POST",
			url: form.attr('action'),
			data: post,
			success: function(data){
				eval('var res = '+data+';');
				if(res.result=='success') $('#connect_result').html('<div class="success">'+BX.message("ESOL_IX_SOURCE_LAUTH_SUCCESS")+'</div>');
				else $('#connect_result').html('<div class="fail">'+BX.message("ESOL_IX_SOURCE_LAUTH_FAIL")+'</div>');
			},
			error: function(){
				$('#connect_result').html('<div class="fail">'+BX.message("ESOL_IX_SOURCE_LAUTH_FAIL")+'</div>');
			},
			timeout: 20000
		});
	}
}

var EImport = {
	params: {},

	Init: function(post, params)
	{
		BX.scrollToNode($('#resblock .adm-info-message')[0]);
		this.wait = BX.showWait();
		this.post = post;
		if(typeof params == 'object') this.params = params;
		this.SendData();
		this.pid = post.PROFILE_ID;
		this.idleCounter = 0;
		this.errorStatus = false;
		var obj = this;
		setTimeout(function(){obj.SetTimeout();}, 3000);
	},
	
	SetTimeout: function()
	{
		if($('#progressbar').hasClass('end')) return;
		var obj = this;
		this.timer = setTimeout(function(){obj.GetStatus();}, 2000);
	},
	
	GetStatus: function()
	{
		var obj = this;
		$.ajax({
			type: "GET",
			url: '/upload/tmp/'+esolIXModuleName+'/'+this.pid+'.txt?hash='+(new Date()).getTime(),
			success: function(data){
				var finish = false;
				if(data && data.substr(0, 1)=='{' && data.substr(data.length-1)=='}')
				{
					try {
						eval('var result = '+data+';');
					} catch (err) {
						var result = false;
					}
				}
				else
				{
					var result = false;
				}
				
				if(typeof result == 'object')
				{
					if(result.action!='finish')
					{
						obj.UpdateStatus(result);
					}
					else
					{
						obj.UpdateStatus(result, true);
						var finish = true;
					}
				}
				if(!finish) obj.SetTimeout();
			},
			error: function(){
				obj.SetTimeout();
			},
			timeout: 5000
		});
	},
	
	UpdateStatus: function(result, end)
	{
		if($('#progressbar').hasClass('end')) return;
		if(end && this.timer) clearTimeout(this.timer);
		
		if(typeof result == 'object')
		{
			result.total_file_line = parseInt(result.total_file_line);
			if(!result.total_file_line) result.total_file_line = 1;
			
			if(end && (parseInt(result.total_read_line) < parseInt(result.total_file_line)))
			{
				result.total_read_line = result.total_file_line;
			}
			
			$('#total_line').html(result.total_line);
			$('#correct_line').html(result.correct_line);
			$('#error_line').html(result.error_line);
			$('#element_added_line').html(result.element_added_line);
			$('#element_updated_line').html(result.element_updated_line);
			$('#element_removed_line').html(result.element_removed_line);
			$('#sku_added_line').html(result.sku_added_line);
			$('#sku_updated_line').html(result.sku_updated_line);
			$('#section_added_line').html(result.section_added_line);
			$('#section_updated_line').html(result.section_updated_line);
			$('#killed_line').html(result.killed_line);
			$('#offer_killed_line').html(result.offer_killed_line);
			$('#zero_stock_line').html(result.zero_stock_line);
			$('#offer_zero_stock_line').html(result.offer_zero_stock_line);
			$('#old_removed_line').html(result.old_removed_line);
			$('#offer_old_removed_line').html(result.offer_old_removed_line);
			
			var span = $('#progressbar .presult span');

			if(result.curstep && span.attr('data-'+result.curstep))
			{
				span.html(span.attr('data-'+result.curstep));
			}
			if(end)
			{
				span.css('visibility', 'hidden');
				$('#progressbar .presult').removeClass('load');
				$('#progressbar').addClass('end');
			}
			var percent = Math.abs(Math.round((result.total_read_line / result.total_file_line) * 100));
			if(percent >= 100) percent = 99;
			if(end) percent = 100;
			$('#progressbar .presult b').html(percent+'%');
			$('#progressbar .pline').css('width', percent+'%');
			
			if(this.tmpparams && this.tmpparams.total_read_line==result.total_read_line)
			{
				this.idleCounter++;
			}
			else
			{
				this.idleCounter = 0;
			}
			this.tmpparams = result;
		}
		
		/*if(this.idleCounter > 10 && this.errorStatus)
		{
			var obj = this;
			for(var i in obj.tmpparams)
			{
				obj.params[i] = obj.tmpparams[i];
			}
			obj.SendDataSecondary();
		}*/
	},
	
	SendData: function()
	{
		var post = this.post;
		post.ACTION = 'DO_IMPORT';
		post.stepparams = this.params;
		var obj = this;
		
		$.ajax({
			type: "POST",
			url: window.location.href,
			data: post,
			success: function(data){
				obj.errorStatus = false;
				obj.OnLoad(data);
			},
			error: function(){
				obj.errorStatus = true;
				$('#block_error_import').show();
				var timeBlock = document.getElementById('esol_ix_auto_continue_time');
				if(timeBlock)
				{
					timeBlock.innerHTML = '';
					obj.TimeoutOnAutoConinue();
				}
			},
			timeout: (post.STEPS_TIME ? ((Math.min(3600, post.STEPS_TIME) + 120) * 1000) : 180000)
		});
	},
	
	TimeoutOnAutoConinue: function()
	{
		var obj = this;
		var timeBlock = document.getElementById('esol_ix_auto_continue_time');
		var time = timeBlock.innerHTML;
		if(time.length==0)
		{
			timeBlock.innerHTML = 30;
		}
		else
		{
			time = parseInt(time) - 1;
			timeBlock.innerHTML = time;
			if(time < 1)
			{
				//$('#kda_ie_continue_link').trigger('click');

				$.ajax({
					type: "POST",
					url: window.location.href,
					data: {'MODE': 'AJAX', 'PROCCESS_PROFILE_ID': obj.pid, 'ACTION': 'GET_PROCESS_PARAMS'},
					success: function(data){
						if(data && data.substr(0, 1)=='{' && data.substr(data.length-1)=='}')
						{
							try {
								eval('var params = '+data+';');
							} catch (err) {
								var params = false;
							}
							if(typeof params == 'object')
							{
								obj.params = params;
							}
						}
						$('#block_error_import').hide();
						obj.errorStatus = false;
						obj.SendDataSecondary();
					},
					error: function(){
						timeBlock.innerHTML = '';
						obj.TimeoutOnAutoConinue();
					}
				});
				return;
			}
		}
		setTimeout(function(){obj.TimeoutOnAutoConinue();}, 1000);
	},
	
	SendDataSecondary: function()
	{
		var obj = this;
		if(this.post.STEPS_DELAY)
		{
			setTimeout(function(){
				obj.SendData();
			}, parseInt(this.post.STEPS_DELAY) * 1000);
		}
		else
		{
			obj.SendData();
		}
	},
	
	OnLoad: function(data)
	{
		data = $.trim(data);
		var returnLabel = '<!--module_return_data-->';
		if(data.indexOf(returnLabel)!=-1)
		{
			data = $.trim(data.substr(data.indexOf(returnLabel) + returnLabel.length));
		}
		if(data.indexOf('{')!=0)
		{
			this.SendDataSecondary();
			return true;
		}
		try {
			eval('var result = '+data+';');
		} catch (err) {
			var result = false;
		}
		if(typeof result == 'object')
		{
			if(result.sessid)
			{
				$('#sessid').val(result.sessid);
			}
			
			if(typeof result.errors == 'object' && result.errors.length > 0)
			{
				$('#block_error').show();
				for(var i=0; i<result.errors.length; i++)
				{
					$('#res_error').append('<div>'+result.errors[i]+'</div>');
				}
			}
			
			if(result.action=='continue')
			{
				this.UpdateStatus(result.params);
				this.params = result.params;
				this.SendDataSecondary();
				return true;
			}
		}
		else
		{
			this.SendDataSecondary();
			return true;
		}

		this.UpdateStatus(result.params, true);
		BX.closeWait(null, this.wait);
		/*$('#res_continue').hide();
		$('#res_finish').show();*/
	
		return false;
	}
}

var ESettings = {
	AddValue: function(link)
	{
		var div = $(link).prev('div').clone(true);
		$('input, select', div).val('').show();
		$(link).before(div);
	},
	
	OnValChange: function(select)
	{
		var input = $(select).next('input');
		var val = $(select).val();
		if(val.length > 0) input.hide();
		else input.show();
		input.val(val);
	},
	
	AddMargin: function(link)
	{
		var div = $(link).closest('td').find('.esol-ix-settings-margin:eq(0)');
		if(!div.is(':visible'))
		{
			div.show();
		}
		else
		{
			var div2 = div.clone(true);
			$('select, input', div2).val('');
			$(link).before(div2);
		}
	},
	
	RemoveMargin: function(link)
	{
		var divs = $(link).closest('td').find('.esol-ix-settings-margin');
		if(divs.length > 1)
		{
			$(link).closest('.esol-ix-settings-margin').remove();
		}
		else
		{
			$('select, input', divs).val('');
			divs.hide();
		}
	},
	
	ShowMarginTemplateBlock: function(link)
	{
		$('#margin_templates_load').hide();
		var div = $('#margin_templates');
		div.toggle();
	},
	
	ShowMarginTemplateBlockLoad: function(link, action)
	{
		$('#margin_templates').hide();
		var div = $('#margin_templates_load');
		if(action == 'hide') div.hide();
		else div.toggle();
	},
	
	SaveMarginTemplate: function(input, message)
	{
		var div = $(input).closest('div');
		var tid = $('select[name=MARGIN_TEMPLATE_ID]', div).val();
		var tname = $('input[name=MARGIN_TEMPLATE_NAME]', div).val();
		if(tid.length==0 && tname.length==0) return false;
		
		var wm = BX.WindowManager.Get();
		var url = wm.PARAMS.content_url;
		var params = wm.GetParameters().replace(/(^|&)action=[^&]*($|&)/, '&').replace(/^&+/, '').replace(/&+$/, '')
		params += '&action=save_margin_template&template_id='+tid+'&template_name='+tname;
		$.post(url, params, function(data){
			var jData = $(data);
			$('#margin_templates').replaceWith(jData.find('#margin_templates'));
			$('#margin_templates_load').replaceWith(jData.find('#margin_templates_load'));
			alert(message);
		});
		
		return false;
	},
	
	LoadMarginTemplate: function(input)
	{
		var div = $(input).closest('div');
		var tid = $('select[name=MARGIN_TEMPLATE_ID]', div).val();
		if(tid.length==0) return false;
		
		var wm = BX.WindowManager.Get();
		var url = wm.PARAMS.content_url;
		var params = wm.GetParameters().replace(/(^|&)action=[^&]*($|&)/, '&').replace(/^&+/, '').replace(/&+$/, '')
		params += '&action=load_margin_template&template_id='+tid;
		var obj = this;
		$.post(url, params, function(data){
			var jData = $(data);
			$('#settings_margins').replaceWith(jData.find('#settings_margins'));
			obj.ShowMarginTemplateBlockLoad('hide');
		});
		
		return false;
	},
	
	RemoveMarginTemplate: function(input, message)
	{
		var div = $(input).closest('div');
		var tid = $('select[name=MARGIN_TEMPLATE_ID]', div).val();
		if(tid.length==0) return false;
		
		var wm = BX.WindowManager.Get();
		var url = wm.PARAMS.content_url;
		var params = wm.GetParameters().replace(/(^|&)action=[^&]*($|&)/, '&').replace(/^&+/, '').replace(/&+$/, '')
		params += '&action=delete_margin_template&template_id='+tid;
		$.post(url, params, function(data){
			var jData = $(data);
			$('#margin_templates').replaceWith(jData.find('#margin_templates'));
			$('#margin_templates_load').replaceWith(jData.find('#margin_templates_load'));
			alert(message);
		});
		
		return false;
	},
	
	BindConversionEvents: function()
	{
		$('.esol-ix-settings-conversion').each(function(){
			var parent = this;
			$('select.field_cell', parent).bind('change', function(){
				if(this.value=='ELSE' || this.value=='LOADED')
				{
					$('select.field_when', parent).hide();
					$('input.field_from', parent).hide();
				}
				else
				{
					$('select.field_when', parent).show();
					$('input.field_from', parent).show();
				}
			}).trigger('change');
		});
	},
	
	AddConversion: function(link)
	{
		var prevDiv = $(link).prev('.esol-ix-settings-conversion');
		if(!prevDiv.is(':visible'))
		{
			prevDiv.show();
		}
		else
		{
			var div = prevDiv.clone();
			$('select, input', div).not('.choose_val').val('');
			$(link).before(div);
		}
		ESettings.BindConversionEvents();
	},
	
	RemoveConversion: function(link)
	{
		var div = $(link).closest('.esol-ix-settings-conversion');
		if($(link).closest('td').find('.esol-ix-settings-conversion').length > 1)
		{
			div.remove();
		}
		else
		{
			$('select, input', div).not('.choose_val').val('');
			div.hide();
		}
	},
	
	ShowChooseVal: function(btn)
	{
		var field = $(btn).prev('input')[0];
		this.focusField = field;
		var arLines = [];
		for(var key in admKDASettingMessages)
		{
			if(key.indexOf('RATE_')==0)
			{
				var currency = key.substr(5);
				arLines.push({'TEXT':admKDASettingMessages[key],'TITLE':'#'+currency+'# - '+admKDASettingMessages[key],'ONCLICK':'ESettings.SetUrlVar(\'#'+currency+'#\')'});
			}
		}
		
		if(admKDASettingMessages.AVAILABLE_TAGS)
		{
			var tags = admKDASettingMessages.AVAILABLE_TAGS;
			for(var i in tags)
			{
				arLines.push({'TEXT':tags[i],'TITLE':'{'+i+'}','ONCLICK':'ESettings.SetUrlVar(\'{'+i+'}\')'});
			}
		}
		BX.adminShowMenu(btn, arLines, '');
	},
	
	ShowExtraChooseVal: function(btn)
	{
		var field = $(btn).prev('input')[0];
		this.focusField = field;
		var arLines = [];
		for(var k in admKDASettingMessages.EXTRAFIELDS)
		{
			arLines.push({'TEXT':'<b>'+admKDASettingMessages.EXTRAFIELDS[k].TITLE+'</b>', 'HTML':'<b>'+admKDASettingMessages.EXTRAFIELDS[k].TITLE+'</b>', 'TITLE':'#'+k+'# - '+admKDASettingMessages.EXTRAFIELDS[k].TITLE,'ONCLICK':'javascript:void(0)'});
			for(var k2 in admKDASettingMessages.EXTRAFIELDS[k].FIELDS)
			{
				arLines.push({'TEXT':admKDASettingMessages.EXTRAFIELDS[k].FIELDS[k2], 'TITLE':'#'+k2+'# - '+admKDASettingMessages.EXTRAFIELDS[k].FIELDS[k2],'ONCLICK':'ESettings.SetUrlVar(\'#'+k2+'#\')'});
			}
		}
		BX.adminShowMenu(btn, arLines, '');
	},
	
	ShowPHPExpression: function(link)
	{
		var div = $(link).next('.esol-ix-settings-phpexpression');
		if(div.is(':visible')) div.hide();
		else div.show();
	},
	
	SetUrlVar: function(id)
	{
		var obj_ta = this.focusField;
		//IE
		if (document.selection)
		{
			obj_ta.focus();
			var sel = document.selection.createRange();
			sel.text = id;
			//var range = obj_ta.createTextRange();
			//range.move('character', caretPos);
			//range.select();
		}
		//FF
		else if (obj_ta.selectionStart || obj_ta.selectionStart == '0')
		{
			var startPos = obj_ta.selectionStart;
			var endPos = obj_ta.selectionEnd;
			var caretPos = startPos + id.length;
			obj_ta.value = obj_ta.value.substring(0, startPos) + id + obj_ta.value.substring(endPos, obj_ta.value.length);
			obj_ta.setSelectionRange(caretPos, caretPos);
			obj_ta.focus();
		}
		else
		{
			obj_ta.value += id;
			obj_ta.focus();
		}

		BX.fireEvent(obj_ta, 'change');
		obj_ta.focus();
	},
	
	AddDefaultProp: function(select)
	{
		if(!select.value) return;
		var parent = $(select).closest('tr');
		var inputName = 'DEFAULTS['+select.value+']';
		if($(parent).closest('table').find('input[name="'+inputName+'"]').length > 0) return;
		var tmpl = parent.prev('tr.esol-ix-list-settings-defaults');
		var tr = tmpl.clone();
		tr.css('display', '');
		$('.adm-detail-content-cell-l', tr).html(select.options[select.selectedIndex].innerHTML+':');
		$('input[type=text]', tr).attr('name', inputName);
		tr.insertBefore(tmpl);
		$(select).val('').trigger('chosen:updated');
	},
	
	RemoveDefaultProp: function(link)
	{
		$(link).closest('tr').remove();
	},
	
	RemoveLoadingRange: function(link)
	{
		$(link).closest('div').remove();
	},
	
	AddNewLoadingRange: function(link)
	{
		var div = $(link).prev('div');
		var newRange = div.clone().insertBefore(div);
		newRange.show();
	},
}

var EHelper = {
	ShowHelp: function(index)
	{
		var dialog = new BX.CAdminDialog({
			'title':BX.message("ESOL_IX_POPUP_HELP_TITLE"),
			'content_url':'/bitrix/admin/'+esolIXModuleFilePrefix+'_popup_help.php',
			'width':'900',
			'height':'450',
			'resizable':true});
			
		BX.addCustomEvent(dialog, 'onWindowRegister', function(){
			$('#esol-ix-help-faq > li > a').bind('click', function(){
				var div = $(this).next('div');
				if(div.is(':visible')) div.stop().slideUp();
				else div.stop().slideDown();
				return false;
			});
			
			if(index > 0)
			{
				$('#esol-ix-help-tabs .esol-ix-tabs-heads a:eq('+parseInt(index)+')').trigger('click');
			}
		});
			
		dialog.Show();
	},
	
	SetTab: function(link)
	{
		var parent = $(link).closest('.esol-ix-tabs');
		var heads = $('.esol-ix-tabs-heads a', parent);
		var bodies = $('.esol-ix-tabs-bodies > div', parent);
		var index = 0;
		for(var i=0; i<heads.length; i++)
		{
			if(heads[i]==link)
			{
				index = i;
				break;
			}
		}
		heads.removeClass('active');
		$(heads[index]).addClass('active');
		
		bodies.removeClass('active');
		$(bodies[index]).addClass('active');
	}
}

$(document).ready(function(){
	/*Bug fix with excess jquery*/
	var anySelect = $('select:eq(0)');
	if(typeof anySelect.chosen != 'function')
	{
		var jQuerySrc = $('script[src^="/bitrix/js/main/jquery/"]').attr('src');
		if(jQuerySrc)
		{
			$.getScript(jQuerySrc, function(){
				$.getScript('/bitrix/js/'+esolIXModuleName+'/chosen/chosen.jquery.min.js');
			});
		}
	}
	/*/Bug fix with excess jquery*/
		
	if($('#preview_file').length > 0)
	{
		var post = $('#preview_file').closest('form').serialize() + '&ACTION=SHOW_REVIEW_LIST';
		$.post(window.location.href, post, function(data){
			$('#preview_file').html(data);
			EIXPreview.Init();
		});
	}

	EProfile.Init();
	
	if($('#esol-ix-updates-message').length > 0)
	{
		$.post('/bitrix/admin/'+esolIXModuleFilePrefix+'.php?lang='+BX.message('LANGUAGE_ID'), 'MODE=AJAX&ACTION=SHOW_MODULE_MESSAGE', function(data){
			data = $(data);
			var inner = $('#esol-ix-updates-message-inner', data);
			if(inner.length > 0 && inner.html().length > 0)
			{
				$('#esol-ix-updates-message-inner').replaceWith(inner);
				$('#esol-ix-updates-message').show();
			}
		});
	}
});