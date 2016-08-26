<?php

class CustomerpricesProcessor extends Magmi_ItemProcessor {

	protected $_cpcol = array();


	public function getPluginInfo()
	{
		return array(
			'name'=>'Customer Price Importer',
			'author'=>'Andreas Bilz,herooutoftime',
			'version'=>'0.0.1'
		);
	}

	public function processItemAfterId(&$item, $params = null)
	{
		$pid = $params["product_id"];
		$cpn = $this->tablename($this->getParam("CUSTPRI:table", "customerprices_prices"));
		$cpcol = array_intersect(array_keys($this->_cpcol), array_keys($item));

		// Do nothing if item has no customer price info or has not change
		if (count($cpcol) == 0)
			return true;

		// Get all Mage-customers by their wawi-customer-id
		// CAUTION: Multiple Mage-customers possible
		$customers = $this->getCustomers();

		// Remove previously created entries
		$sql = "DELETE FROM {$cpn} WHERE product_id = {$pid}";
		$this->delete($sql);

		// Prepare basic SQL-query
		$sql = "INSERT INTO $cpn
			(customer_id, product_id, store_id, qty, price, special_price, customer_email, created_at, updated_at) VALUES (:customer_id, :product_id, :store_id, :qty, :price, :special_price, :customer_email, :created_at, :updated_at)";

		foreach ($cpcol as $k) {
			// get customer price column info
			$cpinf = $this->_cpcol[$k];
			$_wawi_knr = $cpinf['name'];
			foreach ($customers[$_wawi_knr] as $customer) {
				$price = str_replace(",", ".", $item[$k]);
				$special_price = str_replace(",", ".", $item[$k]);
				$data = array(
					'customer_id' => $customer['id'],
					'product_id' => $pid,
					'store_id' => '0',
					'qty' => 1,
					'price' => $price,
					'special_price' => $special_price,
					'customer_email' => $customer['email'],
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				);
				// Execute SQL-query
				$this->insert($sql, $data);
			}
		}
		return true;
	}

	public function getCustomers()
	{
		$customer_ident = $this->getParam("CUSTPRI:customer_ident", "wawi_customer_id");
		$customer_ids = array_column($this->_cpcol, 'name');
		$customerColl = Mage::getModel("customer/customer")->getCollection();
		if($customer_ident != 'id')
			$customerColl->addAttributeToSelect($customer_ident);

		$customerColl
			->addAttributeToFilter($customer_ident, array('in' => $customer_ids))
			->getSelect()
			->reset(Zend_Db_Select::COLUMNS)
			->columns('entity_id AS id')
			->columns('email');

		if($customer_ident != 'id')
			$customerColl->getSelect()->columns("at_{$customer_ident}.value AS $customer_ident");

		$customers = $customerColl->getData();
		foreach ($customers as $index => $customer) {
			$customers[$customer[$customer_ident]][] = $customer;
			unset($customers[$index]);
		}
		return $customers;
	}

	public function processColumnList(&$cols, $params = null)
	{
		$pattern = $this->getParam("CUSTPRI:column_name", "customer_price");
		foreach ($cols as $col) {
			if (preg_match("|{$pattern}:(.*)|", $col, $matches)) {
				$tpinf = array("name" => $matches[1], "id" => null);
				$this->_cpcol[$col] = $tpinf;
			}
		}
		return true;
	}

	public function initialize($params)
	{

	}
}