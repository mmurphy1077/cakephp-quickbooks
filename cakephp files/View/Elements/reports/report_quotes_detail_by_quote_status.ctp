<?php
// LEAD BY 
// Account Rep 
$title = "Quotes Summary Report";
if($display_details) {
	$title = 'Quotes Detail Report';
}

$html = "";
$html = $html . '<div id="application_report_container">';
$html = $html . '	<div id="report_header_container" class="abstract page_element group">';
$html = $html . '		<div class="company_header">' . $applicationSettings['ApplicationSetting']['company_name'] . '</div>';
$html = $html . '		<div class="title">' . __($title) . '</div>';
$html = $html . '<div class="date_range">' . $this->Web->dt($target_report['start_date'], 'short_4') . ' - ' . $this->Web->dt($target_report['end_date'], 'short_4') . '</div>';
$html = $html . '<div class="top_left">' . date('m/d/Y') . '</div>';
$html = $html . '		<div class="top_right screen_only title-buttons">' . $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')) . '</div>';
$html = $html . '</div>';

$html = $html . '	<div id="report_stats_container" class="abstract page_element">';
$html = $html . '		<div class="report_table_container">';
$html = $html . '			<table>';
$html = $html . '				<tr><td class="rowrap">Number of Quotes created during this period: ' . count($result['data']) . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Total value of Quotes: $' . number_format($result['stats'][0][0]['price_total'], 2, '.', ',') . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Quotes marked as Unsubmitted: ' . $result['stats'][0][0]['quote_unsubmitted'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Quotes marked as Submitted: ' . $result['stats'][0][0]['quote_submitted'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Sold Quotes: ' . $result['stats'][0][0]['quote_sold'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Quote to Job Ratio: ';
if(!empty($result['stats'][0][0]['quote_sold'])) {
$html = $html .					number_format(count($result['data']) / $result['stats'][0][0]['quote_sold'], 2) . '/1 &nbsp;&nbsp;(' . number_format(($result['stats'][0][0]['quote_sold'] / count($result['data']) * 100), 2) . '%)';
}
$html = $html . '				</td></tr>';$html = $html . '			</table>';
$html = $html . '		</div>';
$html = $html . '	</div>';

$html = $html . '<div id="report_detail_container" class="abstract page_element">';
		if(empty($result['data'])) : 
$html = $html . '<span class="no_data">' . __('No data exists for the parameters selected.') . '</span>';
		endif; 
		$current_sort_header = 'start';
		foreach($result['data'] as $data) :
			$insert_table_close = false;
			$display_sort_header = false;
			if ($data['Quote']['status'] != $current_sort_header) {
				$insert_table_close = true;
				if($current_sort_header == 'start') {
					$insert_table_close = false;
				}				
				
				$current_sort_header = $data['Quote']['status'];
				if(empty($current_sort_header)) {
					$current_sort_header_dislpay = __('Unassigned');
				} else {
					$current_sort_header_dislpay = $result['status'][$data['Quote']['status']];
				}
				$display_sort_header = true;
			} 
			if($insert_table_close):
$html = $html . '				</table>';
$html = $html . '			</div>';
			endif; 
			if($display_sort_header) :
$html = $html . '			<div class="sort_header">';
$html = $html . '				<div class="label inline">By Status:</div>';
$html = $html . '				<b>' . $current_sort_header_dislpay . '</b>';
$html = $html . '			</div>';
$html = $html . '			<div class="report_table_container">';
$html = $html . '				<table>';
$html = $html . '					<tr>';
$html = $html . '						<th class="rowrap">Date</th>';
$html = $html . '						<th class="rowrap">Job Name</th>';
$html = $html . '						<th class="rowrap">Customer Name</th>';
$html = $html . '						<th class="rowrap">Submitted</th>';
$html = $html . '						<th class="rowrap">Assigned</th>';
$html = $html . '						<th class="rowrap">Last Activity</th>';
$html = $html . '						<th class="rowrap">Ammount</th>';
$html = $html . '					</tr>';
			endif; 
			/*
			 * TABLE DATA
			 */
$html = $html . '			<tr>';
$html = $html . '			 	<td>' . $this->Web->dt($data['Quote']['created'], 'short_4') . '</td>';
$html = $html . '			 	<td>' . $data['Quote']['name'] . '</td>';
$html = $html . '			 	<td>'; 
			 		$company = $data['Quote']['customer_name'];
			 		$name = $data['Quote']['contact_name'];
			 		$temp = str_replace(', ', '', $name);
			 		if(empty($temp)) {
						$name = '';
					} else {
						if(!empty($company)) {
							$name = ' - ' . $name;
						}
					}
			 		if(!empty($company)) {
$html = $html . 		$company;
					}
$html = $html . 	$name;
$html = $html . '			 	</td>';
$html = $html . '			 	<td>' . $this->Web->dt($data['Quote']['submitted'], 'short_4') . '</td>';
$html = $html . '			 	<td>'; 
			 		if(!empty($data['AccountRep']['account_rep_name_first'])) {
$html = $html . 		$data['AccountRep']['account_rep_name_first'] . ' ';
					}
					if(!empty($data['AccountRep']['account_rep_name_last'])) {
$html = $html . 		$data['AccountRep']['account_rep_name_last'];
					}
$html = $html . '			 	</td>';
$html = $html . '			 	<td>' . $this->Web->dt($data['ActionLog']['last_activity'], 'short_4') . '</td>';
$html = $html . '			 	<td>' . '$'.number_format($data['Quote']['price_total'], 2) . '</td>';
$html = $html . '			</tr>';
			if($display_details): 
$html = $html . '			<tr class="details">';
$html = $html . '				<td>&nbsp;</td>';
$html = $html . '				<td colspan="6">' . nl2br($data['Quote']['description']) . '</td>';
$html = $html . '			</tr>';
			endif; 
		endforeach; 
$html = $html . '			</table>';
$html = $html . '		</div>';
$html = $html . '	</div>';
$html = $html . '</div>';
echo $html;