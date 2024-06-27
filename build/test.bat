set HOME=..\plugins\ajax\
set OUTDIR=..\plugins\ajax\

for /f %%f in ('dir /b /s /a:d %HOME%') do (
	if exist %HOME%%%~nf.zip del %HOME%%%~nf.zip
	if exist %OUTDIR%%%~nf.zip del %OUTDIR%%%~nf.zip
)

