Report config
=============

Bank Transfers
--------------

# Old
BGZ : "{{i}}; {{file.bankAccount}}; {{file.name}}; {{file.address1}}; {{file.code}}; {{file.city}}; honorarium dla {{file.name}} zg. z rach. {{doc.docNo}} - projekt {{project.name}} {% if FirstIF.IAAccNo == 700 %}, {{FirstIF.netto}} PLN płatne ze śr. {{FirstIF.IName}} {% endif %}; {{IBA.netto|number_format(2, '.', '')}}"

# New - kw;nazwa;rach;tyt
BGZ : "{{IBA.netto|number_format(2, ',', '')}}; {{file.name}}; {{file.bankAccount}}; ; honorarium dla {{file.name}} zg. z rach. {{doc.docNo}} - projekt {{project.name}} {% if FirstIF.IAAccNo == 700 %}, {{FirstIF.netto}} PLN płatne ze śr. {{FirstIF.IName}} {% endif %}; "

# Old
BGZ:  "{{param.TO_PDOF_bank_acc}};N;{{param.organization_NIP}};{{form.payment_date|date('y')}};M;{{form.payment_date|date('m')}};PIT-4R; - ;{{value.tax}}"

# New 
BGZ:  "{{value.tax|number_format(2, ',', '')}};PIT-4R;N;1132421207;Urząd Skarbowy Warszawa-Mokotów;97 1010 1010 0165 4822 2300 0000;76 2030 0045 1110 0000 0396 5940;{{form.payment_date|date('y')}};M;{{form.payment_date|date('m')}}"


Bilans
------
```yaml
columns:
  begining_of_year:
   formulas:
     bookkentry:
       account: arg1
       side: arg2
       bookk:
         doc:
           year: { this.Year }
           doc_cats:
             symbol: {BO}
  end_of_year: 
   formulas:
     bookkentry:
       account: arg1
       side: arg2
       bookk:
         doc:
           year: { this.Year }
           doc_cats:
             symbol: {^BO}
tools: {exportCSV}
```
RZiS
----
```yaml
columns:
  prev_year:
   formulas:
     bookkentry:
       account: arg1
       side: arg2
       bookk:
         doc:
           year: { this.Year.prev }
  this_year:
   formulas:
     bookkentry:
       account: arg1
       side: arg2
       bookk:
         doc:
           year: { this.Year }
tools: {exportCSV}
```           
Pit4r
-----
```yaml
columns:
  this_year:
   formulas:
     bookkentry:
       account: arg1
       side: arg2
       desc: arg4
       bookk:
         doc:
           month:
             name: arg3
           doc_cat:
             year: { this.Year }
tools: {exportXML}
```
# $Reports->fields[i]=array(
#  'name'=>'X'
#  'formula'=>'bookkentry:225-001:1:14M10'
#  'symbol'=>'P_24'
#  'columns'=>array(
#    'this_year'=>...))

Pit11
-----
```yaml
collection_of: 
  file:
    file_cat:
      symbol: {ZB}
      year: {this.Year, this.Year.prev}
   order_by: {year.from_date, desc}
   group_by: file.PESEL  # as coll_idx
groups:   
  uod_pa:
    contract:
      file: 
        PESEL: coll_idx
      type: UoD_PA
      payment_date: {min: this.Year.fromDate, max: this.Year.toDate}
      columns: {income, income_cost, gross, tax}
      project:~
    group_by: project.name
formulas:
  file:
    column: arg1
  item:
    group: arg1
    column: arg2
tools: {exportXML, uploadPDF, sendEmails}
```
# $Report->fields[i]=array(
#  'no'=>1,
#  'level'=>1,
#  'name'=>'X'
#  'formula'=>'item:uod_pa:income'
#  'symbol'=>'P_44')
#
# $Report->collection[i]=array(
#  'file'=>$File, 
#  'group'=>array('uod_pa'=>array(
#    'columns'=>array('income'=>..., ...),
#    'group_by'=>array(
#      'projekt ABC'=>array('income'=>..., ...))),
#  'fields'=>array(.. )
#
# $Report->collection[i+1]=array(
#  'file'=>null,
#  'group'=>array('uod_pa'=>array(
#    'columns'=>array('income'=>..., ...),
#    'group_by'=>array(
#      'projekt ABC'=>array('income'=>..., ...)))   
                      
Booking templates
=================

I200-845
--------
```yaml
group: Income
rows : EACH
Doc : SelectedDoc
desc : __Doc_desc__ zg.z. __Doc_no__ 
BEs : 
 - {cols : SUM, side : 1, value : gross, Account : CommitAcc, FileLev1 : GroupFile }
 - {cols : SUM, side : 2, value : gross, Account : #845, FileLev1 : ProjectFile, FileLev2 : GroupFile }
```
I130-200
--------
```yaml
group: Income
rows : EACH
Doc : PaymentDoc
desc : płatność od __GroupFile_Name__ - projekt __Project_Name__
BEs : 
 - {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }
 - {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : GroupFile }
```
I130-701-001
------------
```yaml
group: Income
rows : EACH
Doc : PaymentDoc
desc : Darowizna __ID_desc__ na projekt __Project_Name__
BEs : 
- {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }
- {cols : SUM, side : 2, value : gross, Account : #701-001 }
```
I130-701-002
------------
```yaml
group: Income
rows : EACH
Doc : PaymentDoc
desc : Darowizna __ID_desc__ na projekt __Project_Name__
BEs : 
- {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }
- {cols : SUM, side : 2, value : gross, Account : #701-002 }
```
I845-700
--------
```yaml
group: Cost
rows : SUM
Doc : DocByNo
desc : __Doc_desc__ zg.z. __Doc_no__ 
BEs : 
 - {cols : IF, side : 1, value : gross, Account : #845, FileLev1 : ProjectFile, FileLev2 : IncomeFile } 
 - {cols : IF, side : 2, value : gross, Account : IncomeAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile }
```
C500-231
--------
```yaml
group: Cost
rows : EACH
Doc : SelectedDoc
desc : __Doc_desc__ zg.z. __Doc_no__
BEs : 
 - {cols : IF,  side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}
 - {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : SelDocFile }
 - {cols : SUM, side : 1, value : tax, Account : CommitAcc, FileLev1 : SelDocFile }
 - {cols : SUM, side : 2, value : tax, Account : TaxCommitAcc }
``` 
C231-130
--------
```yaml
group: Cost
rows : EACH
Doc : PaymentDoc
desc : honorarium dla __SelDocFile_Name__ - projekt __Project_Name__
BEs : 
 - {cols : SUM, side : 1, value : netto, Account : CommitAcc, FileLev1 : SelDocFile }
 - {cols : IBA, side : 2, value : netto, Account : IncomeBankAcc }
```
C225-130
--------
```yaml
group: Cost
rows : SUM
Doc : PaymentDoc
desc : __YY__M__MM__ podatek PDOF, projekt __Project_Name__ 
BEs : 
 - {cols : SUM, side : 1, value : tax, Account : TaxCommitAcc }
 - {cols : IBA, side : 2, value : tax, Account : IncomeBankAcc }
```
C500-201
--------
```yaml
group: Cost
rows : EACH
Doc : SelectedDoc
desc : __Doc_desc__ zg.z. __Doc_no__
BEs : 
- {cols : IF, side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}
- {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : SelDocFile }
```
C201-130
--------
```yaml
group: Cost
rows : EACH
Doc : PaymentDoc
desc : __SelDocFile_Name__ zg. z  __SelDoc_No__ - projekt __Project_Name__
BEs : 
- {cols : SUM, side : 1, value : netto, Account : CommitAcc, FileLev1 : SelDocFile }
- {cols : IBA, side : 2, value : netto, Account : IncomeBankAcc }
```


