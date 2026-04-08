$f = 'c:\laragon\www\oopedia_skripsi\public\css\mahasiswa.css'
$c = Get-Content $f -Raw
$mark = '/* end tour styles */'
$i = $c.IndexOf($mark) + $mark.Length
$trimmed = $c.Substring(0, $i) + "`n"
[System.IO.File]::WriteAllText($f, $trimmed)
Write-Host "Done, file size:" (Get-Item $f).Length
