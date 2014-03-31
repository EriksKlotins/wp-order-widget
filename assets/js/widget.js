;(function($, window)
{

	var WTFOrderWidget = function()
	{
		var strings = 
		{
			confirmRemoveAll:'Remove All?'
		};
		

		var widgetNode;

		var writeValues = function()
		{
			var list = [];
			$('table tr',widgetNode).each(function(i,row){
				var code = $('input[name="code"]',row).val();
				var qty = $('input[name="qty"]',row).val();
				list.push({code:code, qty:qty});
			});

			var data = 
			{
				mail  : $('input[name="email"]', widgetNode).val(),
				name  : $('input[name="name"]', widgetNode).val(),
				rows  : list
			}
			window.localStorage.setItem('WTFOrderWidget',JSON.stringify(data));
		}

		var getValues = function()
		{
			var rows = window.localStorage.getItem('WTFOrderWidget');
			return (!rows) ? {rows:[], email:'', name:''} :JSON.parse(rows);
		}
		var readValues = function()
		{
			
			var data = getValues();
			console.log(data);
			var rows = data.rows;
			$('input[name="email"]', widgetNode).val(data.mail);
			$('input[name="name"]', widgetNode).val(data.name);
			for(var i=0;i<rows.length;i++)
			{
				addRow(rows[i].code, rows[i].qty);
			}
			if (rows.length>0)
			{

				$('.first-row', widgetNode).remove();
				$('table tr:first-child').addClass('first-row');
			}
			
			//console.log(rows);
		}
		var addRow = function (code, qty)
		{
			var row = $('table tr.first-row',widgetNode).clone();
			$(row).removeClass('first-row');
			$('input[name="code"]', row).val(code);
			$('input[name="qty"]', row).val(qty);
			$('table',widgetNode).append(row);
		}
		var onAddMoreClick = function()
		{
			addRow('',1);
			return false;
		}
		var removeAll = function()
		{
			$('table tr', widgetNode).not('.first-row').remove();
			$('.first-row input',widgetNode).val('');
			writeValues();
		}
		var onRemoveAllClick = function()
		{
			if (confirm(strings.confirmRemoveAll))
			{
				removeAll();
			}
			return false;
		}
		var onFieldExit = function()
		{
			writeValues();
		}

		var onFormSubmit = function()
		{
			var data = 
			{
				mail  : $('input[name="email"]', widgetNode).val(),
				name  : $('input[name="name"]', widgetNode).val(),
				
				rows  : JSON.stringify(getValues().rows),
				action: 'wtf_order_widget_submit'
			};
			$.post(ajaxurl, data, function(response) {
				alert('Order submitted!');
				removeAll();
				//alert('Got this from the server: ' + response);
			});

			return false;
		}

		var initialize = function()
		{
			widgetNode = $('.wtf-order-widget');
			$('#wtf-ev-add-more').click(onAddMoreClick);
			$('#wtf-ev-remove-all').click(onRemoveAllClick);
			$('#wtf-ev-submit').click(onFormSubmit);
			$('input[name="qty"], input[name="code"], input[name="email"], input[name="name"]', widgetNode).live('blur',onFieldExit)
			readValues();
		}

		$(document).ready(initialize);
		
	}
	
	new WTFOrderWidget();

})(jQuery, window);