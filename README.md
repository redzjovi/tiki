## Synopsis
PHP track your Tiki.

## Installation
```
composer require redzjovi/tiki
```

## How to use
```
use redzjovi\tiki\Tiki;

$tiki = new Tiki();

/*
 * @param [string] $resi
 * @return [array] $result
 * [
 *      'resi' => '030071590590',
 *      'status' => 'Success \/ RECEIVED BY: HARIS',
 *      'service' => 'tiki',
 *      'service_code' => 'ONS',
 *      'tanggal' => '17-Jul 14:08',
 *      'dikirim_tanggal' => '14 July, 2017',
 *      'estimasi_sampai'=> '15 July, 2017',
 *      'pengirim' => 'BODYFITSTATION.COM',
 *      'penerima' => 'MULUK',
 *      'dikirim_dari => 'JAKARTA-',
 *      'dikirim_ke => 'JL.KYAI SAHLAN 21\/02 MANYAR -GRESIK',
 *      'status' => 'Success / RECEIVED BY: HARIS',
 *      'history => [
 *          'tanggal' => '17-Jul 14:08',
 *          'status' => 'Success \/ RECEIVED BY: HARIS',
 *          'lokasi' => 'Di [GRESIK]',
 *          'keterangan' => '',
 *      ]'
 * ]
 */   
$track = $tiki->track('030071590590');

var_dump($track);
```