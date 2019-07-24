<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function SendError($number, $text)
{
	SendUploadResults($number, '', '', $text);
}

require './config.php';
require './util.php';
require './io.php';
require './commands.php';
require './phpcompat.php';

if (!$Config['Enabled']) {
	SendUploadResults('1', '', '', 'This file uploader is disabled. Please check the "editor/filemanager/connectors/php/config.php" file');
}

$sCommand = 'QuickUpload';
$sType = isset($_GET['Type']) ? $_GET['Type'] : 'File';
$sCurrentFolder = GetCurrentFolder();

if (!IsAllowedCommand($sCommand)) {
	SendUploadResults('1', '', '', 'The ""' . $sCommand . '"" command isn\'t allowed');
}

if (!IsAllowedType($sType)) {
	SendUploadResults(1, '', '', 'Invalid type specified');
}

FileUpload($sType, $sCurrentFolder, $sCommand);

?>
