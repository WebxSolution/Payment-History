setlocal enabledelayedexpansion enableextensions
:: SET HOME DIR
XCopy . ..\temp /EXCLUDE:build\exclude.txt /E /C /R /I /K /Y

set TMPDIR=build\tmpdir\
set SUITE=..\temp\suite\pkg_paymenthistory\

mkdir build\tmpdir
mkdir %TMPDIR%pkg_hostbill

XCOPY %SUITE%* %TMPDIR%pkg_paymenthistory /D /E /C /R /I /K /Y

:: Run BAT Files to update Zip files

set PLUGINS=(user content extension installer system quickicon inline template-xtd task)

for /f "delims=" %%f in ('dir/b /s /a:d %SUITE%packages') do  (
	set /a PACKAGE=1

	for %%i in %PLUGINS% do (
		if %%~nf==%%i (
			set /a PACKAGE=0
			call build\make-packages.bat plugins %%~nf
		)
	)

	if !PACKAGE!==1 (
		call build\make-packages.bat %%~nf
	)
) 

:: CLEAR OUT EXISTING PACKAGES
if exist pkg_paymenthistory.zip del pkg_paymenthistory.zip
if exist suite/pkg_paymenthistory.zip del suite/pkg_paymenthistory.zip

:: PACKAGE TEMP DIR
"c:\Program Files\7-Zip\7z" a -tzip pkg_paymenthistory.zip .\%TMPDIR%pkg_paymenthistory\

:: MOVE PACKAGE TO SUITE FOLDER
move pkg_paymenthistory.zip suite\pkg_paymenthistory.zip
::move pkg_paymenthistory.zip ..\tags\pkg_paymenthistory.zip

:: DELETE TMP DIR
rmdir build\tmpdir /s /q
rmdir ..\temp /s /q