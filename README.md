# MAGMI Customerprices Itemprocessor Plugin

Plugin to import customer prices for [http://www.webtexsoftware.com/prices-per-customer-magento-extension](Webtex Customer Prices) via MAGMI (Magento Mass Importer)

## Options:

* `table`: The table where the customer prices should be stored
* `column_name`: The name of the column in your CSV or SQL-Datasource
* `customer_ident`: How to identify a customer or a group of customers
 
### Customer Ident
The option `customer_ident` allows to fetch grouped customers by a attribute which is present in every customer entity.
E.g.: ERP has several specialists grouped under a customer-ID. If that's the case you can change the value of `customer_ident`
to the column to identify one or multiple customers:

In this example your `customer_ident` value would be `customer_id`

| customer_id  | specialist_name | specialist_email
|---|---|---|
| 1111 | Max Mustermann | max.mustermann@example.com
| 1111 | Max Musterfrau | max.musterfrau@example.com


**Please be aware: This plugin is still under development and should not be used in productive environments**