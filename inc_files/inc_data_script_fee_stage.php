	
<script type=\"text/javascript\">
<!--

function comboItemSelected(oList1,oList2){
	if (oList2!=null){
		clearComboOrList(oList2);
			if (oList1.selectedIndex == -1){
		oList2.options[oList2.options.length] = new Option('Please make a selection from the list', '');
		} else {
			fillCombobox(oList2, oList1.name + '=' + oList1.options[oList1.selectedIndex].value);
		}
	}
}

function clearComboOrList(oList){
	for (var i = oList.options.length - 1; i >= 0; i--){
		oList.options[i] = null;
	}
		oList.selectedIndex = -1;
	if (oList.onchange)	oList.onchange();
}

function fillCombobox(oList, vValue){

	if (vValue != '') {
		if (assocArray[vValue]){
			oList.options[0] = new Option('-- Select Stage --', '');
			var arrX = assocArray[vValue];
			for (var i = 0; i < arrX.length; i = i + 2){
				if (arrX[i] != 'EOF') oList.options[oList.options.length] = new Option(arrX[i + 1].split('&amp;').join('&'), arrX[i]);
			}
			if (oList.options.length == 1){
				oList.selectedIndex=0;
				if (oList.onchange) oList.onchange();
			}
		} else {
			oList.options[0] = new Option('-- None --', '');
		}
	}
}

//-->
</script>