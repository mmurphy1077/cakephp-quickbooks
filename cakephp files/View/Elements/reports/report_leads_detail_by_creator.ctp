<?php
// LEAD BY 
// Creator
$title = "Leads Summary Report";
if($display_details) {
	$title = 'Leads Detail Report';
}

$html = "";
$html = $html . '<div id="application_report_container">';
$html = $html . '	<div id="report_header_container" class="abstract page_element group">';
$html = $html . '		<div class="company_header">' . $applicationSettings['ApplicationSetting']['company_name'] . '</div>';
$html = $html . '		<div class="title">' . __($title) . '</div>';
$html = $html . '		<div class="date_range">' . $this->Web->dt($target_report['start_date'], 'short_4') . ' - ' . $this->Web->dt($target_report['end_date'], 'short_4') . '</div>';
$html = $html . '		<div class="top_left">' . date('m/d/Y') . '</div>';
$html = $html . '		<div class="top_right screen_only title-buttons">' . $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')) . '</div>';
$html = $html . '	</div>';

$html = $html . '	<div id="report_stats_container" class="abstract page_element">';
$html = $html . '		<div class="report_table_container">';
$html = $html . '			<table>';
$html = $html . '				<tr><td class="rowrap">Number of Leads created during this period: ' . count($result['data']) . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Leads marked as No Contact: ' . $result['stats'][0][0]['lead_no_contact'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Leads marked as Contact Made: ' . $result['stats'][0][0]['lead_contact_made'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Leads marked as Meeting Scheduled: ' . $result['stats'][0][0]['lead_meeting_scheduled'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Leads that have lead to a quote: ' . $result['stats'][0][0]['lead_became_quoted_customer'] . '</td></tr>';
$html = $html . '				<tr><td class="rowrap">Number of Leads associated with a job: ' . $result['stats'][0][0]['lead_has_order_associated'] . '</td></tr>';
$html = $html . '			</table>';
$html = $html . '		</div>';
$html = $html . '	</div>';

$html = $html . '	<div id="report_detail_container" class="abstract page_element">';
		if(empty($result['data'])) : 
$html = $html . '<span class="no_data">' . __('No data exists for the parameters selected.') . '</span>';
		endif; 
		$current_sort_header = 'start';
		foreach($result['data'] as $data) :
			$insert_table_close = false;
			$display_sort_header = false;
			if ($data['Lead']['creator_id'] != $current_sort_header) {
				$insert_table_close = true;
				if($current_sort_header == 'start') {
					$insert_table_close = false;
				}				
				
				$current_sort_header = $data['Lead']['creator_id'];
				if(empty($current_sort_header)) {
					$current_sort_header_dislpay = __('Unassigned');
				} else {
					$current_sort_header_dislpay = $data['Creator']['creator_name_last'] . ', ' . $data['Creator']['creator_name_first'];
				}
				
				$display_sort_header = true;
			} 
			if($insert_table_close) :
$html = $html . '				</table>';
$html = $html . '			</div>';
			endif; 
			if($display_sort_header) :
$html = $html . '			<div class="sort_header">';
$html = $html . '				<div class="inline">Created By:</div>';
$html = $html . '				<b>' . $current_sort_header_dislpay . '</b>';
$html = $html . '			</div>';
$html = $html . '			<div class="report_table_container">';
$html = $html . '				<table>';
$html = $html . '					<tr>';
$html = $html . '						<th>Created</th>';
$html = $html . '						<th>Lead Name</th>';
$html = $html . '						<th>Last Contact</th>';
$html = $html . '					</tr>';
			endif; 
			/*
			 * TABLE DATA
			 */
$html = $html . '			<tr>';
$html = $html . '			 	<td>' . $this->Web->dt($data['Lead']['created'], 'short_4') . '</td>';
$html = $html . '			 	<td>';
			 		$company = $data['Lead']['company_name'];
			 		$name = $this->Web->humanName($data['Lead'], 'reverse');
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
$html = $html . '			 	<td>' . $this->Web->dt($data['Lead']['date_last_contact_made'], 'short_4') . '</td>';
$html = $html . '			</tr>';
			if($display_details): 
$html = $html . '			<tr class="details">';
$html = $html . '				<td>&nbsp;</td>';
$html = $html . '				<td colspan="3">' . nl2br($data['Lead']['notes_internal']) . '</td>';
$html = $html . '			</tr>';
			endif; 
		endforeach;
$html = $html . '			</table>';
$html = $html . '		</div>';
$html = $html . '	</div>';
$html = $html . '</div>';
echo $html;