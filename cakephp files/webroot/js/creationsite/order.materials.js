$(document).ready(function(){
	
	function calcMarkup(perc, cost) {
		perc = perc/100;
		var val = ((cost * perc) + Number(cost)).toFixed(2);
		return val;
	}
	
	function calcMargin(perc, cost) {
		perc = perc/100;
		var val = (cost/(1-perc)).toFixed(2);
		return val;
	}
	
	function calcCurrentPerc(type) {
		var actual =  $("input#OrderMaterialItem0PricePerUnitActual").val(),
			list = $("input#OrderMaterialItem0PricePerUnit").val();

		if(list.length && actual.length) {
			if(type == 'margin') {
				if(list.length && Number(list) > 0) {
					perc=1-(actual/list);
					perc =  (perc*100).toFixed(2);
				} else {
					perc = 'NA';
				}
			} else {
				// markup
				if(actual.length && Number(actual) > 0) {
					perc=(list - actual)/actual;
					perc =  (perc*100).toFixed(2);
				} else {
					perc = 'NA';
				}
			}
		} else {
			perc = '';
		}
		return perc;
	}
	
	$(document).on("click", 'div.markup-type-checkbox-container input[type="checkbox"]', function( evt ) {
        var type = 'margin';
		if($(this).attr('id') == 'markup_typeMarkup') {
			type = 'markup';
		}
		$('#markup_perc').val(calcCurrentPerc(type));
    });
	
	$(document).on("click", 'a#calculate_markup', function( evt ) {
        var perc = $('input#markup_perc').val(),
        	cost = $("input#OrderMaterialItem0PricePerUnitActual").val(),
        	list = 0;
        
        if($('input#markup_typeMarkup').prop('checked')) {
        	list = calcMarkup(perc, cost);
        } else {
        	list = calcMargin(perc, cost);
        }
      
        $("input#OrderMaterialItem0PricePerUnit").val(list);
       
        return false;
    });
});