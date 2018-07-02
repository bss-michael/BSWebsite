@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../../../../../app/vendor/nikic/php-parser/bin/php-parse
php "%BIN_TARGET%" %*
