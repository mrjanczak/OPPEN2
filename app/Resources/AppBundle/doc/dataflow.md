Procedures
----------
contract
	contract_date
	payment_period*

contracts
	payment_period(if empty, use contract->payment_period)
	
>>>
	
generate_docs form	
	receive_date   
	document_date  
	operation_date  
	document_month* (last active)
	
>>>	
	
document	
	receive_date    (now)
	document_date   (contract_date)
	operation_date	(now)
	bookking_date   (now)
	payment_deadline_date  =  receive_date + payment_period
	payment_date    (empty)
