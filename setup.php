<?php

	$categoryList = array(
		'sb2CharactorKana'=>'ひらがなが含まれているか？',
		'sb2Charactor'=>'日本語(2バイト以上の文字)が含まれているか？',
		'sb2Length'=>'1行の文字数がN文字を超えていないか？',
		'sb2FeedCount'=>'連続した改行の合計がN個を超えていないか？',
		'sb2UrlCount'=>'URLが含まれているか？',
		'sb2NgWord'=>'NGワードが含まれているか？',
		'sb2Uribl'=>'URLがURIBLデータベースに登録されているか？',
		'sb2Dnsbl'=>'投稿者のIPがDNSBLデータベースに登録されているか？',
	);

	$categoryTarget = array(
		'author'=>'名前欄',
		'url'=>'URL欄',
		'content'=>'コメント欄',
	);

?>

	<div class="wrap">
		<h2>SPAM-BYE2設定</h2>
		<h3 id="spambye2result" style="color:#ff0000;"><?php echo $_POST['_SB2_RESULT']; ?></h3>
		<h3>基本設定</h3>

		<form method="post" action="admin.php?page=spam-bye2">
		<input type="hidden" name="spam-bye2_update" value="1" />
		<input type="hidden" name="SB2_ENTRY_OBJECT[]" value="<?php echo $_POST['SB2_ENTRY_OBJECT'][0]; ?>" id="spambye2ObjectNum" />

		<table class="widefat">
		<thead>
		<tr>
			<th style="width:30%;border-right:1px solid #dfdfdf;">項目</th>
			<th style="width:70%;">値</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定する閾値</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_LEVEL[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_LEVEL'][0]); ?>" /></td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_LEVEL']['error']; ?></td>
		</tr>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合のアクション</th>
			<td style="border-bottom:0;">
				<select name="SB2_SPAM_ACTION[]" class="postform">
				<option value="0"<?php if (!$_POST['SB2_SPAM_ACTION'][0]) { echo ' selected="selected"'; } ?>>指定したメッセージを表示</option>
				<option value="1"<?php if ($_POST['SB2_SPAM_ACTION'][0]) { echo ' selected="selected"'; } ?>>指定したURLにリダイレクト</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_ACTION']['error']; ?></td>
		</tr>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合に表示するメッセージ</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_MESSAGE[]" type="text" size="80" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_MESSAGE'][0]); ?>" /></td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_MESSAGE']['error']; ?></td>
		</tr>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">スパムと判定された場合のリダイレクトURL</th>
			<td style="border-bottom:0;"><input name="SB2_SPAM_REDIRECT[]" type="text" size="80" class="search-input" value="<?php echo htmlspecialchars($_POST['SB2_SPAM_REDIRECT'][0]); ?>" /></td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_SPAM_REDIRECT']['error']; ?></td>
		</tr>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">DNSBLデータベース</th>
			<td style="border-bottom:0;"><textarea name="SB2_DNSBL_HOSTS[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['SB2_DNSBL_HOSTS'][0]); ?></textarea></td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;"><?php echo $_POST['SB2_DNSBL_HOSTS']['error']; ?></td>
		</tr>
		<tr>
			<th rowspan="2" style="border-right:1px solid #dfdfdf;border-bottom:0;">URIBLデータベース</th>
			<td style="border-bottom:0;"><textarea name="SB2_URIBL_HOSTS[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['SB2_URIBL_HOSTS'][0]); ?></textarea></td>
		</tr>
		<tr>
			<td style="color:red;border-top:0;border-bottom:0;"><?php echo $_POST['SB2_URIBL_HOSTS']['error']; ?></td>
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
			<th colspan="7" style="width:65%;white-space:nowrap;">値</th>
		</tr>
		</thead>
		<tbody>
		<?php
			for ($i = 1; $i <= $_POST['SB2_ENTRY_OBJECT'][0]; $i++) {
				$defName = 'SB2_OBJECT_' . $i;
				$defVal = $_POST[$defName][0];

				$lastStyle = ($i == $_POST['SB2_ENTRY_OBJECT'][0] ? "border-bottom:0;" : null);
			?>

		<tr class="spambye2Column">
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

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td colspan="5" style="border-bottom:0;white-space:nowrap;width:85%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Length":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算ポイントの最大値</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">許容文字数</td>
			<td style="border-bottom:0;white-space:nowrap;width:45%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2FeedCount":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">チェック改行数</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">許容改行数</td>
			<td style="border-bottom:0;white-space:nowrap;width:45%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2NgWord":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算ポイントの最大値</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">NGワード</td>
			<td style="border-bottom:0;white-space:nowrap;width:45%;"><textarea name="<?php echo "SB2_NGWORD_${i}"; ?>[]" rows="5" cols="50" class="search-input"><?php echo htmlspecialchars($_POST['SB2_NGWORD_'.$i][0]); ?></textarea></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2UrlCount":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算ポイントの最大値</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">許容URL数</td>
			<td style="border-bottom:0;white-space:nowrap;width:45%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][4]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Uribl":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算ポイントの最大値</td>
			<td colspan="3" style="border-bottom:0;white-space:nowrap;width:65%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][3]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					case "sb2Dnsbl":
			?>

			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算するポイント</td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][1]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:10%;">加算ポイントの最大値</td>
			<td colspan="3" style="border-bottom:0;white-space:nowrap;width:65%;"><input name="<?php echo $defName; ?>[]" type="text" size="5" class="search-input" value="<?php echo htmlspecialchars($_POST[$defName][2]); ?>" /></td>
			<td style="border-bottom:0;white-space:nowrap;width:5%;"><input type="button" class="button spambye2DelColumn" value="×" /></td>
			<?php
						break;
					default:
						break;
				}
			?>

		</tr>
		<tr class="spambye2ErrorColumn">
			<td colspan="7" style="color:red;border-top:0;<?php echo $lastStyle; ?>"<?php if ($lastStyle) echo " class='spambye2LastColumn'"; ?>><?php echo $_POST[$defName]['error']; ?></td>
		</tr>
		<?php
			}
		?>

		</tbody>
		</table>
		<p><input type="button" id="spambye2PutColumn" class="button" value="1行追加" /><input type="submit" class="button" value="　保存　" /></p>

		</form>
	</div>