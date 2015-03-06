<?php

	$categoryList = array(
		'sb2CharactorKana'=>'ひらがなが含まれていない',
		'sb2Charactor'=>'日本語(2バイト以上の文字)が含まれていない',
		'sb2Length'=>'1行の文字数がN文字を超えている',
		'sb2FeedCount'=>'連続した改行の合計がN個を超えている',
		'sb2UrlCount'=>'URLが含まれている',
		'sb2NgWord'=>'NGワードが含まれている',
		'sb2Uribl'=>'URLがURIBLデータベースに登録されている',
		'sb2Dnsbl'=>'投稿者のIPがDNSBLデータベースに登録されている',
	);

	$categoryTarget = array(
		'author'=>'名前欄',
		'url'=>'URL欄',
		'content'=>'コメント欄',
	);

?>

	<div class="wrap">
		<h2>SPAM-BYEBYE設定</h2>
		<h3 id="spambye2result" style="color:#ff0000;"><?php echo $_POST['_SB2_RESULT']; ?></h3>
		<h3>基本設定</h3>

		<form method="post" action="admin.php?page=spam-byebye">
		<input type="hidden" name="spam-byebye_update" value="1" />
		<input type="hidden" name="SB2_ENTRY_OBJECT[]" value="<?php echo $_POST['SB2_ENTRY_OBJECT'][0]; ?>" id="spambye2ObjectNum" />

		<table class="widefat">
		<thead>
		<tr>
			<th style="width:30%;border-right:1px solid #dfdfdf;">項目</th>
			<th style="width:70%;">値</th>
		</tr>
		</thead>
		<tbody>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定する閾値</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_LEVEL[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_LEVEL'][0]); ?>" /></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_LEVEL']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合のアクション</th>
			<td style="border-bottom:0;">
				<select name="SB2_SPAM_ACTION[]" class="postform">
				<option value="0"<?php if (!$_POST['SB2_SPAM_ACTION'][0]) { echo ' selected="selected"'; } ?>>指定したメッセージを表示</option>
				<option value="1"<?php if ($_POST['SB2_SPAM_ACTION'][0]) { echo ' selected="selected"'; } ?>>指定したURLにリダイレクト</option>
				</select>
			</td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_ACTION']['error']; ?></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合に表示するメッセージ</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_MESSAGE[]" type="text" size="80" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_MESSAGE'][0]); ?>" /></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_MESSAGE']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合のリダイレクトURL</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_REDIRECT[]" type="text" size="80" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_REDIRECT'][0]); ?>" /></td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_REDIRECT']['error']; ?></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">DNSBLデータベース</th>
			<td style="border-bottom:0;"><textarea name="SB2_DNSBL_HOSTS[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['SB2_DNSBL_HOSTS'][0]); ?></textarea></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_DNSBL_HOSTS']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">URIBLデータベース</th>
			<td style="border-bottom:0;"><textarea name="SB2_URIBL_HOSTS[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['SB2_URIBL_HOSTS'][0]); ?></textarea></td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_URIBL_HOSTS']['error']; ?></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;border-bottom:0;">ホワイトリスト<br />(メールアドレス)</th>
			<td style="border-bottom:0;"><textarea name="SB2_WHITE_LISTS[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['SB2_WHITE_LISTS'][0]); ?></textarea></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;border-bottom:0;"><?php echo $_POST['SB2_WHITE_LISTS']['error']; ?></td>
		</tr>
		</tbody>
		</table>

		<h3>チェック項目設定</h3>

		<table id="spambye2CheckTable" class="widefat">
		<thead>
		<tr>
			<th style="width:10%;border-right:1px solid #dfdfdf;text-align:center;white-space:nowrap;">優先度</th>
			<th style="width:20%;border-right:1px solid #dfdfdf;white-space:nowrap;">チェック内容</th>
			<th style="width:5%;border-right:1px solid #dfdfdf;white-space:nowrap;">チェック対象</th>
			<th colspan="2" style="width:65%;white-space:nowrap;">値</th>
		</tr>
		</thead>
		<tbody>
		<?php
			for ($i = 1; $i <= $_POST['SB2_ENTRY_OBJECT'][0]; $i++) {
				$defName = 'SB2_OBJECT_' . $i;
				$defVal = $_POST[$defName][0];

				$lastStyle = ($i == $_POST['SB2_ENTRY_OBJECT'][0] ? "border-bottom:0;" : null);
				$bgcol = ($i % 2 != 0 ? '#f9f9f9' : '#ececec');
			?>

		<tr class="spambye2Column" style="background-color:<?php echo $bgcol ?>;">
			<td rowspan="2" style="border-right:1px solid #dfdfdf;white-space:nowrap;text-align:center;<?php echo $lastStyle; ?>"<?php if ($lastStyle) echo " class='spambye2LastColumn'"; ?>>
				<input type="button" class="button spambye2UpColumn" value="↑" />
				<input type="button" class="button spambye2DownColumn" value="↓" />
			</td>
			<td rowspan="2" style="border-right:1px solid #dfdfdf;<?php echo $lastStyle; ?>"<?php if ($lastStyle) echo " class='spambye2LastColumn'"; ?>>
				<select name="<?php echo $defName; ?>[]" class="postform spambye2ChangeColumn">
				<?php
					foreach ($categoryList as $key=>$val) {
						if ($key === $_POST[$defName][0]) {
				?>
				<option value="<?php echo $key; ?>" selected="selected"><?php echo $val; ?></option>
				<?php
						} else {
				?>
				<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
				<?php
						}
					}
				?>
				</select>
			</td>
			<td rowspan="2" style="border-right:1px solid #dfdfdf;<?php echo $lastStyle; ?>"<?php if ($lastStyle) echo " class='spambye2LastColumn'"; ?>>
				<?php
					if ($defVal !== "sb2Dnsbl") {
				?>

				<select name="<?php echo $defName; ?>[]" class="postform">
				<?php
					foreach ($categoryTarget as $key=>$val) {
						if ($key === $_POST[$defName][1]) {
				?>
				<option value="<?php echo $key; ?>" selected="selected"><?php echo $val; ?></option>
				<?php
						} else {
				?>
				<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
				<?php
						}
					}
				?>
				</select>
				<?php
					} else {
				?>

				&nbsp;
				<?php
					}
				?>

			</td>
			<?php
				switch ($defVal) {
					case "sb2CharactorKana":
					case "sb2Charactor":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Length":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></div>
				<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容文字数</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2FeedCount":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">チェック改行数</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></div>
				<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容改行数</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2NgWord":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></div>
				<div style="clear:both;width:135px;float:left;">NGワード</div>
				<div style="float:left;width:60%;"><textarea name="<?php echo "SB2_NGWORD_${i}"; ?>[]" rows="5" cols="35" class="search-input" style="width:100%;"><?php echo htmlspecialchars($_POST['SB2_NGWORD_'.$i][0]); ?></textarea></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2UrlCount":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></div>
				<div style="clear:both;width:135px;float:left;height:30px;line-height:30px;">許容URL数</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Uribl":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Dnsbl":
			?>

			<td style="border-bottom:0;width:60%;">
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算するポイント</div>
				<div style="float:left;margin-right:5px;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][1]); ?>" /></div>
				<div style="width:135px;float:left;height:30px;line-height:30px;">加算ポイントの最大値</div>
				<div style="float:left;height:30px;line-height:30px;"><input name="<?php echo $defName; ?>[]" type="text" size="3" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></div>
			</td>
			<td style="border-bottom:0;width:5%;text-align:right;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					default:
						break;
				}
			?>

		</tr>
		<tr class="spambye2ErrorColumn" style="background-color:<?php echo $bgcol ?>;">
			<td colspan="2" style="color:red;border-top:0;<?php echo $lastStyle; ?>"<?php if ($lastStyle) echo " class='spambye2LastColumn'"; ?>><?php echo $_POST[$defName]['error']; ?></td>
		</tr>
		<?php
			}
		?>

		</tbody>
		</table>
		<p><input type="button" id="spambye2PutColumn" class="button" value="1行追加" /><input type="submit" class="button" value="　保存　" /></p>

		</form>
	</div>