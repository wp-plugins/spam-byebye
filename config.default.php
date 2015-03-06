<?php

/****************************************************** 基本設定 ******************************************************/

// スパムと判定する閾値
define('SB2_SPAM_LEVEL', 100);

// スパムと判定された場合のアクション
// 0:SB2_SPAM_MESSAGEに指定したメッセージを表示
// 1:SB2_SPAM_REDIRECTに指定したURLにリダイレクト
define('SB2_SPAM_ACTION', 0);

// スパムと判定された場合に表示するメッセージ
// SB2_SPAM_ACTIONが0の場合のみ有効
define('SB2_SPAM_MESSAGE', '403 Forbidden');

// スパムと判定された場合のリダイレクトURL
// SB2_SPAM_ACTIONが1の場合のみ有効
define('SB2_SPAM_REDIRECT', 'http://');

// DNSBLデータベース
// 複数指定する場合は「,」で区切る
define('SB2_DNSBL_HOSTS', 'b.barracudacentral.org');

// URIBLデータベース
// 複数指定する場合は「,」で区切る
define('SB2_URIBL_HOSTS', 'url.rbl.jp,dyndns.rbl.jp,notop.rbl.jp,multi.surbl.org,multi.uribl.com');

// ホワイトリスト(メールアドレス)
// 複数指定する場合は「,」で区切る
define('SB2_WHITE_LISTS', '');

/************************************************** チェック項目設定 ***************************************************

[チェック内容]
	sb2CharactorKana	:ひらがなが含まれていない
	sb2Charactor		:日本語(2バイト以上の文字)が含まれていない
	sb2Length			:1行の文字数がN文字を超えている
	sb2FeedCount		:連続した改行の合計がN個を超えている
	sb2UrlCount			:URLが含まれている
	sb2NgWord			:NGワードが含まれている
	sb2Uribl			:URLがURIBLデータベースに登録されている
	sb2Dnsbl			:投稿者のIPがDNSBLデータベースに登録されている

[チェック対象]
	author				:名前欄の入力値
	url					:url欄の入力値
	content				:コメント欄の入力値

[チェックの順番]
	SB2_OBJECT_番号の小さい順にチェックします

[フォーマット]
	sb2CharactorKana	:define('SB2_OBJECT_番号', 'sb2CharactorKana,チェック対象,加算するポイント');
	sb2Charactor		:define('SB2_OBJECT_番号', 'sb2Charactor,チェック対象,加算するポイント');
	sb2Length			:define('SB2_OBJECT_番号', 'sb2Length,チェック対象,加算するポイント,加算ポイントの最大値,許容文字数');
	sb2FeedCount		:define('SB2_OBJECT_番号', 'sb2FeedCount,チェック対象,加算するポイント,チェック改行数,許容改行数');
	sb2NgWord			:define('SB2_OBJECT_番号', 'sb2NgWord,チェック対象,加算するポイント,加算ポイントの最大値');
	sb2UrlCount			:define('SB2_OBJECT_番号', 'sb2UrlCount,チェック対象,加算するポイント,加算ポイントの最大値,許容URL数');
	sb2Uribl			:define('SB2_OBJECT_番号', 'sb2Uribl,チェック対象,加算するポイント,加算ポイントの最大値');
	sb2Dnsbl			:define('SB2_OBJECT_番号', 'sb2Dnsbl,加算するポイント,加算ポイントの最大値');

	sb2NgWord用NGワード	:define('SB2_NGWORD_番号', 'NGワード1,NGワード2,NGワード3');

	(注意)
	SB2_OBJECT_番号は必ず連番にして下さい
	SB2_NGWORD_番号とSB2_OBJECT_番号は必ず同じ番号にして下さい

[sb2Lengthについて]
	sb2Lengthはチェック対象の1行あたりの文字数が許容文字数を超過した場合にポイントが加算されます
	許容文字数を超過する行が複数あった場合は該当する行数分ポイントが加算されます
	但し加算ポイントの最大値が0以外の場合はその値が最大値になります

	例)
	define('SB2_OBJECT_1', 'sb2Length,content,20,0,200');
	コメント欄に200文字を超過する行が5箇所存在していた場合は20x5=100ポイントとなります

	define('SB2_OBJECT_1', 'sb2Length,content,20,60,200');
	コメント欄に200文字を超過する行が5箇所存在していた場合(20x5=100)でも60ポイントとなります

[sb2FeedCountについて]
	sb2FeedCountはチェック対象の連続した改行(チェック改行数以上の箇所)の合計が許容改行数を超過した場合にポイントが加算されます

	例)
	define('SB2_OBJECT_1', 'sb2FeedCount,content,20,4,12');
	コメント欄に4個以上の連続した改行が存在しその改行数の合計が12を超過している場合は20ポイントとなります

[sb2UrlCountについて]
	sb2UrlCountはチェック対象に含まれるURL数に応じてポイントが加算されます
	但し加算ポイントの最大値が0以外の場合はその値が最大値になります
	チェックした結果のURL数が許容URL数以下だった場合はポイントは加算されません

	例)
	define('SB2_OBJECT_1', 'sb2UrlCount,content,20,0,0');
	コメント欄にURLが5個存在していた場合は20x5=100ポイントとなります

	define('SB2_OBJECT_1', 'sb2UrlCount,content,20,50,0');
	コメント欄にURLが5個存在していた場合(20x5=100)でも50ポイントとなります

	define('SB2_OBJECT_1', 'sb2UrlCount,content,20,0,5');
	コメント欄にURLが5個存在していた場合(20x5=100)でも0ポイントとなります

[sb2NgWordについて]
	sb2NgWordはチェック対象にSB2_NGWORD_番号のNGワードにマッチした数に応じてポイントが加算されます
	但し加算ポイントの最大値が0以外の場合はその値が最大値になります

	例)
	define('SB2_NGWORD_1', 'NGワード1,NGワード2,NGワード3');
	define('SB2_OBJECT_1', 'sb2NgWord,content,40,0');
	コメント欄にNGワードが2個存在していた場合は40x2=80ポイントとなります

	define('SB2_NGWORD_1', 'NGワード1,NGワード2,NGワード3');
	define('SB2_OBJECT_1', 'sb2NgWord,content,40,80');
	コメント欄にNGワードが3個存在していた場合(40x3=120)でも80ポイントとなります

	sb2NgWordを複数指定してグループ毎に加算するポイントを変えることができます

	define('SB2_NGWORD_1', 'NGワード1,NGワード2,NGワード3');
	define('SB2_OBJECT_1', 'sb2NgWord,content,20,0');
	define('SB2_NGWORD_2', 'NGワード4,NGワード5,NGワード6');
	define('SB2_OBJECT_2', 'sb2NgWord,content,30,0');
	define('SB2_NGWORD_3', 'NGワード7,NGワード8,NGワード9');
	define('SB2_OBJECT_3', 'sb2NgWord,content,40,0');

[sb2Uriblについて]
	sb2Uriblに複数のデータベースが指定してある場合、全てのデータベースを参照し登録されていた数だけポイントが加算されます
	但し加算ポイントの最大値が0以外の場合はその値が最大値になります

	例)
	define('SB2_OBJECT_1', 'sb2Uribl,content,20,0');
	コメント欄のURLが3箇所のURIBLに登録されていた場合は20x3=60ポイントとなります

	define('SB2_OBJECT_1', 'sb2Uribl,content,20,40');
	コメント欄のURLが3箇所のURIBLに登録されていた場合(20x3=60)でも40ポイントとなります

[sb2Dnsblについて]
	sb2Dnsblに複数のデータベースが指定してある場合、全てのデータベースを参照し登録されていた数だけポイントが加算されます
	但し加算ポイントの最大値が0以外の場合はその値が最大値になります

	例)
	define('SB2_OBJECT_1', 'sb2Dnsbl,20,0');
	投稿者のIPが3箇所のDNSBLに登録されていた場合は20x3=60ポイントとなります

	define('SB2_OBJECT_1', 'sb2Dnsbl,20,40');
	投稿者のIPが3箇所のDNSBLに登録されていた場合(20x3=60)でも40ポイントとなります

***********************************************************************************************************************/

// チェック項目数(SB2_OBJECT_最後の番号を指定)
define('SB2_ENTRY_OBJECT', 13);

// 名前欄にURLがN個含まれる場合はN個x100ポイント加算(上限無し)
define('SB2_OBJECT_1', 'sb2UrlCount,author,100,0,0');

// URL欄にURLがN個含まれる場合はN個x10ポイント加算(上限10ポイント)
define('SB2_OBJECT_2', 'sb2UrlCount,url,10,10,0');

// 名前欄に日本語が含まれていない場合は20ポイント加算
define('SB2_OBJECT_3', 'sb2Charactor,author,20');

// コメント欄に日本語が含まれていない場合は70ポイント加算
define('SB2_OBJECT_4', 'sb2Charactor,content,70');

// コメント欄にひらがなが含まれていない場合は70ポイント加算
define('SB2_OBJECT_5', 'sb2CharactorKana,content,70');

// コメント欄にURLがN個含まれる場合はN個x30ポイント加算(上限無し)
define('SB2_OBJECT_6', 'sb2UrlCount,content,30,0,0');

// コメント欄に200文字を超える行が含まれる場合はN行x30ポイント加算(上限無し)
define('SB2_OBJECT_7', 'sb2Length,content,30,0,200');

// コメント欄に3個以上の連続した改行の合計が15個を超過した場合は40ポイント加算
define('SB2_OBJECT_8', 'sb2FeedCount,content,40,3,15');

// コメント欄にNGワードが含まれる場合はN個x20ポイント加算(上限無し)
define('SB2_NGWORD_9', 'rolex,会員,price,visa,master');
define('SB2_OBJECT_9', 'sb2NgWord,content,20,0');

// コメント欄にNGワードが含まれる場合はN個x50ポイント加算(上限無し)
define('SB2_NGWORD_10', '不倫,セックス,sex,オナニ,出会,童貞,viagra,SM,人妻,セフレ,18禁');
define('SB2_OBJECT_10', 'sb2NgWord,content,50,0');

// 投稿者のIPがDNSBLに登録されている場合は100ポイント加算(上限無し)
define('SB2_OBJECT_11', 'sb2Dnsbl,100,0');

// URL欄のURLがURIBLに登録されている場合は登録数x90ポイント加算(上限無し)
define('SB2_OBJECT_12', 'sb2Uribl,url,90,0');

// コメント欄のURLがURIBLに登録されている場合は登録数x90ポイント加算(上限無し)
define('SB2_OBJECT_13', 'sb2Uribl,content,90,0');

?>