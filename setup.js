jQuery(function(){
	jQuery('#spambye2PutColumn').click(function()
	{
		jQuery(".spambye2LastColumn").css('border-bottom', '1px solid #dfdfdf');
		jQuery(".spambye2LastColumn").removeAttr('class');

		var rows = jQuery('#spambye2CheckTable tbody').children().length;
			rows = (rows / 2) + 1;
		var bgcol = (rows % 2 != 0 ? '#f9f9f9' : '#ececec');

		jQuery('#spambye2ObjectNum').attr('value', rows);

		jQuery('#spambye2CheckTable tbody').append('<tr class="spambye2Column" style="background-color:'+ bgcol +';">\
			<td rowspan="2" style="border-right:1px solid #dfdfdf;white-space:nowrap;text-align:center;border-bottom:0;" class="spambye2LastColumn">\
				<input type="button" class="button spambye2UpColumn" value="↑" />\
				<input type="button" class="button spambye2DownColumn" value="↓" />\
			</td>\
			<td rowspan="2" style="border-right:1px solid #dfdfdf;border-bottom:0;" class="spambye2LastColumn">\
				<select name="SB2_OBJECT_' + rows + '[]" class="postform spambye2ChangeColumn">\
				<option value="sb2CharactorKana" selected="selected">ひらがなが含まれていない</option>\
				<option value="sb2Charactor">日本語(2バイト以上の文字)が含まれていない</option>\
				<option value="sb2Length">1行の文字数がN文字を超えている</option>\
				<option value="sb2FeedCount">連続した改行の合計がN個を超えている</option>\
				<option value="sb2UrlCount">URLが含まれている</option>\
				<option value="sb2NgWord">NGワードが含まれている</option>\
				<option value="sb2Uribl">URLがURIBLデータベースに登録されている</option>\
				<option value="sb2Dnsbl">投稿者のIPがDNSBLデータベースに登録されている</option>\
				</select>\
			</td>\
			<td rowspan="2" style="border-right:1px solid #dfdfdf;border-bottom:0;" class="spambye2LastColumn">\
				<select name="SB2_OBJECT_' + rows + '[]" class="postform">\
				<option value="author" selected="selected">名前欄</option>\
				<option value="url">URL欄</option>\
				<option value="content">コメント欄</option>\
				</select>\
			</td>\
			<td style="border-bottom:0;width:60%;">\
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
				<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + rows + '[]" type="text" size="3" class="search-input" value="" /></div>\
			</td>\
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>\
		</tr>\
		<tr class="spambye2ErrorColumn" style="background-color:'+ bgcol +';">\
			<td colspan="2" style="color:red;border-top:0;border-bottom:0;" class="spambye2LastColumn"></td>\
		</tr>');
	});

	jQuery('[class^="button spambye2DelColumn"]').live("click", function()
	{
		var rows = jQuery('#spambye2CheckTable tbody').children().length / 2;

		jQuery('#spambye2ObjectNum').attr('value', rows - 1);

		var myObj  = jQuery(this).parent().parent();
		var myObj2 = myObj.next();

		myObj.remove();
		myObj2.remove();

		spambye2SetBgcol();
		spambye2LastClass();
	});

	jQuery('[class^="button spambye2UpColumn"]').live("click", function()
	{
		var myObj  = jQuery(this).parent().parent();
		var myObj2 = myObj.next();

		if (myObj.prev("tr").prev("tr").attr('class')) {
			myObj.insertBefore(myObj.prev("tr").prev("tr"));
			myObj2.insertBefore(myObj2.prev("tr").prev("tr"));

			spambye2SetBgcol();
			spambye2LastClass();
		}
	});

	jQuery('[class^="button spambye2DownColumn"]').live("click", function()
	{
		var myObj  = jQuery(this).parent().parent();
		var myObj2 = myObj.next();

		if (myObj.next("tr").next("tr").attr('class')) {
			myObj.insertAfter(myObj.next("tr").next("tr").next("tr"));
			myObj2.insertAfter(myObj2.next("tr").next("tr").next("tr"));

			spambye2SetBgcol();
			spambye2LastClass();
		}
	});

	jQuery('[class^="postform spambye2ChangeColumn"]').live("change", function()
	{
		var myObj   = jQuery(this).parent().parent();
		var objName = jQuery(this).attr("name").slice(11);
		var type    = jQuery(this).children(':selected').val();
		var rows    = myObj.children().length;
		var tags    = "";

		if (type == "sb2Dnsbl") {
			tags = '<td rowspan="2" style="border-right:1px solid #dfdfdf;">&nbsp;</td>';
		} else {
			tags = '<td rowspan="2" style="border-right:1px solid #dfdfdf;">\
					<select name="SB2_OBJECT_' + objName + '" class="postform">\
					<option value="author" selected="selected">名前欄</option>\
					<option value="url">URL欄</option>\
					<option value="content">コメント欄</option>\
					</select>\
					</td>';
		}

		switch (type) {
			case "sb2CharactorKana":
			case "sb2Charactor":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2Length":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容文字数</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2FeedCount":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">チェック改行数</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容改行数</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2UrlCount":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容URL数</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2NgWord":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="clear:both;width:135px;float:left;">NGワード</div>\
						<div style="float:left;width:60%;"><textarea name="SB2_NGWORD_' + objName + '" rows="5" cols="35" class="search-input" style="width:100%;"></textarea></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2Uribl":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			case "sb2Dnsbl":
				tags = tags + '<td style="border-bottom:0;width:60%;">\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>\
						<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>\
						<div style="float:left;height:30px;line-height:30px;"><input name="SB2_OBJECT_' + objName + '" type="text" size="3" class="search-input" value="" /></div>\
						</td>\
						<td style="border-bottom:0;white-space:nowrap;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>';
				break;
			default:
				break;
		}


		for (i = rows; i > 1; i--) {
			myObj.children("td").eq(i).remove();
		}

		myObj.append(tags);

		spambye2LastClass();
	});

	jQuery('#spambye2result').fadeOut(3000);
});

function spambye2LastClass()
{
	jQuery(".spambye2LastColumn").css('border-bottom', '1px solid #dfdfdf');
	jQuery(".spambye2LastColumn").removeAttr('class');

	var last = jQuery('#spambye2CheckTable tbody tr.spambye2Column:last');

	last.children('td:nth-child(1)').attr('class', 'spambye2LastColumn');
	last.children('td:nth-child(2)').attr('class', 'spambye2LastColumn');
	last.children('td:nth-child(3)').attr('class', 'spambye2LastColumn');
	jQuery('#spambye2CheckTable tbody tr.spambye2ErrorColumn:last').children().attr('class', 'spambye2LastColumn');
	jQuery(".spambye2LastColumn").css('border-bottom', '0');
}

function spambye2SetBgcol()
{
		var $i = 0;
		jQuery('#spambye2CheckTable tbody').children("tr").each(function(){
			if ($i < 2) {
				jQuery(this).css('background-color', '#f9f9f9');
				$i++;
			} else {
				jQuery(this).css('background-color', '#ececec');
				$i++;
			}

			if ($i == 4) $i = 0;
		});
}