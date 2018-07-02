@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../../../../../app/vendor/doctrine/dbal/bin/doctrine-dbal
php "%BIN_TARGET%" %*
