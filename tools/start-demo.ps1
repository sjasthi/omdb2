[CmdletBinding()]
param(
    [string]$AppPath = (Split-Path -Parent $PSScriptRoot),
    [string]$XamppRoot = 'C:\xampp',
    [string]$DatabaseConfig = '',
    [string]$BaseUrl = '',
    [switch]$RequireDemo,
    [switch]$OpenBrowser,
    [switch]$CheckOnly
)
$ErrorActionPreference = 'Stop'
function Test-LocalPort([int]$Port) {
    $socket = [System.Net.Sockets.TcpClient]::new()
    try { return $socket.ConnectAsync('127.0.0.1', $Port).Wait(400) -and $socket.Connected }
    catch { return $false } finally { $socket.Dispose() }
}
function Wait-LocalPort([int]$Port) {
    for ($attempt=0; $attempt -lt 40; $attempt++) {
        if (Test-LocalPort $Port) { return }
        Start-Sleep -Milliseconds 500
    }
    throw "Port $Port did not become ready. Review the server's private log."
}
function Start-LocalServer([string]$Executable, [string[]]$ServerArguments, [string]$Directory, [string]$Name) {
    # Detached servers must not inherit a short-lived terminal's output pipes.
    $logDirectory = Join-Path ([Environment]::GetFolderPath('LocalApplicationData')) 'OMDB2\presentation-logs'
    New-Item -ItemType Directory -Path $logDirectory -Force > $null
    $logStem = Join-Path $logDirectory ($Name + '-' + [Guid]::NewGuid().ToString('N'))
    $launch = @{ FilePath=$Executable; WorkingDirectory=$Directory; WindowStyle='Hidden';
        RedirectStandardOutput=($logStem + '.out.log'); RedirectStandardError=($logStem + '.err.log') }
    if ($ServerArguments.Count) { $launch.ArgumentList = $ServerArguments }
    Start-Process @launch
}
try {
    $AppPath = (Resolve-Path -LiteralPath $AppPath).Path
    $php = Join-Path $XamppRoot 'php\php.exe'
    $checker = Join-Path $AppPath 'tools\check-demo.php'
    if (!(Test-Path -LiteralPath $php) -or !(Test-Path -LiteralPath $checker)) { throw 'PHP or the OMDB preflight tool is missing.' }
    if (!$BaseUrl) { $BaseUrl = 'http://127.0.0.1/' + [Uri]::EscapeDataString((Split-Path -Leaf $AppPath)) + '/' }
    $uri = [Uri]$BaseUrl
    if ($uri.Host -notin @('localhost','127.0.0.1','::1','[::1]') -or $uri.Scheme -notin @('http','https')) { throw 'Use the local OMDB URL for this presentation launcher.' }
    $BaseUrl = $BaseUrl.TrimEnd('/') + '/'
    $settingsText = & $php $checker --port 2>$null
    if ($LASTEXITCODE -ne 0) { throw 'Local database configuration is missing or incomplete. Follow README setup first.' }
    $settings = ($settingsText -join "`n") | ConvertFrom-Json
    if ($settings.host -notin @('localhost','127.0.0.1','::1','[::1]')) { throw 'This launcher starts only local database servers.' }
    $databasePort = [int]$settings.port
    if (!(Test-LocalPort $databasePort)) {
        if ($CheckOnly) { throw "Database port $databasePort is not running." }
        if (!$DatabaseConfig) {
            if ($databasePort -ne 3306) { throw 'Supply -DatabaseConfig for this non-default database instance.' }
            $DatabaseConfig = Join-Path $XamppRoot 'mysql\bin\my.ini'
        }
        $DatabaseConfig = (Resolve-Path -LiteralPath $DatabaseConfig).Path
        Start-LocalServer (Join-Path $XamppRoot 'mysql\bin\mysqld.exe') @("--defaults-file=`"$DatabaseConfig`"",'--bind-address=127.0.0.1','--standalone') (Join-Path $XamppRoot 'mysql') 'database'
        Wait-LocalPort $databasePort
    }
    if (!(Test-LocalPort $uri.Port)) {
        if ($CheckOnly) { throw "Apache port $($uri.Port) is not running." }
        Start-LocalServer (Join-Path $XamppRoot 'apache\bin\httpd.exe') @() $XamppRoot 'apache'
        Wait-LocalPort $uri.Port
    }
    $arguments = @($checker)
    if ($RequireDemo) { $arguments += '--require-demo' }
    $reportText = & $php @arguments 2>$null
    $reportExit = $LASTEXITCODE
    $report = ($reportText -join "`n") | ConvertFrom-Json
    foreach ($check in $report.checks) { Write-Host ((@{ $true='PASS'; $false='FAIL' }[[bool]$check.passed]) + '  ' + $check.check) }
    if ($reportExit -ne 0 -or !$report.ready) { throw 'Presentation checks failed. Correct the failed items before presenting.' }
    $request = [System.Net.HttpWebRequest]::Create($BaseUrl + 'login.php')
    $request.AllowAutoRedirect = $false; $request.Timeout = 10000
    $response = $request.GetResponse()
    $reader = [System.IO.StreamReader]::new($response.GetResponseStream())
    try { $body = $reader.ReadToEnd(); $status = [int]$response.StatusCode }
    finally { $reader.Dispose(); $response.Dispose() }
    if ($status -ne 200 -or $body -notmatch 'name="csrf_token"' -or $body -match 'Fatal error|Stack trace|Warning:') { throw 'The sign-in page did not pass its HTTP check.' }
    Write-Host 'PASS  Apache sign-in page and form token'
    Write-Host "READY  $BaseUrl"
    if ($OpenBrowser) { Start-Process $BaseUrl }
    exit 0
} catch {
    Write-Host ('NOT READY  ' + $_.Exception.Message)
    exit 1
}
