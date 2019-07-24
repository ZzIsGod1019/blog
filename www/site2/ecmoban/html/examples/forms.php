<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
if (isset($_POST['test'])) {
	echo '<pre>';
	echo htmlentities(print_r($_POST, true));
	echo '</pre>';
	exit();
}

ob_start();
include dirname(__FILE__) . '/res/forms.php';
$content = ob_get_clean();
require_once dirname(__FILE__) . '/../html2pdf.class.php';

try {
	$html2pdf = new HTML2PDF('P', 'A4', 'fr');
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output('forms.pdf');
}
catch (HTML2PDF_exception $e) {
	echo $e;
	exit();
}

?>
