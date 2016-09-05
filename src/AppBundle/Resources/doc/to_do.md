To do
=====

Changes in ver.2.7
------------------
* Symfony 2.7
* PSR-2
* Translation
* Tests
* Documentation in .md

Controller
----------
* Refactoring 5-10-20
* Routing annotations
* Security annotations

Model
-----
* Schema annotation
* Reports based on yaml definition
* Contract > type = UoD_PA, UoD, UZl, UoP

View
----
* buttons toolbox snippet

Templates
---------

***Transfer templates***
group: Cost
rows : EACH
Doc  : SelectedDoc
desc : 'honorarium dla __File_name__ zg. z rach. __Doc_no__ - projekt __Project_name__ |__firstIF_netto__ płatne ze śr. __firstIF_name__'
TRANSFERs: 
 - {cols: IBA, bank_name: BGZ, csv: '__No__; __File_bankAccount__; __File_name__; __File_street_flat_no__; __File_code__; __File_city__; __desc__; __IBA_netto__' }

***Booking templates***
group: Cost
rows : EACH
Doc  : SelectedDoc
desc : __Doc_desc__ zg.z. __Doc_no__
BEs  : 
- {cols : IF, side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}
- {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : SelDocFile }

***Report tempate items***

// $p1 -> accNo	
// $p2 -> side
// $p3 -> month_name		
// $p4 -> text in bookk desc

{account:'+701%1', account:'+704%1', month:1, desc:__YY__M01, if:>0}

{account:'+550%|2', node:'-D_1', node:'-D_2', node:'-D_3', node:'-D_4'}

{contractor:’+gross’, contractor:’-income_cost’}



