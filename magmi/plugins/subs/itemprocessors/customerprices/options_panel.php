<ul class="formline">
	<li class="label">Customer Prices Table</li>
	<li class="value"><input type="text" name="CUSTPRI:table"
	                         value="<?php echo $this->getParam("CUSTPRI:table", "customerprices_prices")?>"></input></li>
</ul>
<ul class="formline">
	<li class="label">Customer Prices Column Value (DB or CSV)</li>
	<li class="value"><input type="text" name="CUSTPRI:column_name"
	                         value="<?php echo $this->getParam("CUSTPRI:column_name", "customer_price")?>"></input></li>
</ul>

<ul class="formline">
	<li class="label">Customer Prices Customer Identification</li>
	<li class="value"><input type="text" name="CUSTPRI:customer_ident"
	                         value="<?php echo $this->getParam("CUSTPRI:column_name", "wawi_customer_id")?>"></input></li>
</ul>