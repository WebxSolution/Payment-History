:: SET WORKING DIR
if [%2] == [] (
	set WORKDIR=..\temp\%1
	set OUTDIR=%TMPDIR%pkg_paymenthistory\packages\%1
	set PREFIX=
) else (
	set WORKDIR=..\temp\%1\%2
	set OUTDIR=%TMPDIR%pkg_paymenthistory\packages\%2
	set PREFIX=plg_
)

:: CLEAR OUT EXISTING PACKAGES AND REPACKAGE IN TEMP DIR
for /f "delims=" %%e in ('dir %WORKDIR% /l /b /a:d') do (
	if exist %WORKDIR%\%PREFIX%%%~ne.zip del %WORKDIR%\%PREFIX%%%~ne.zip
	if exist %OUTDIR%\%PREFIX%%%~ne.zip del %OUTDIR%\%PREFIX%%%~ne.zip
	"c:\Program Files\7-Zip\7z" a -tzip %OUTDIR%\%PREFIX%%%~ne.zip .\%WORKDIR%\%%~ne\ 
)