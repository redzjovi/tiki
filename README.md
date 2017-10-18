## Synopsis
PHP track your Tiki.

## Installation
```
composer require redzjovi/tiki
```

## How to use
```
use redzjovi\tiki\Tiki;

/*
 * @param [string] $resi
 * @return [array] $result
 * [
 *      'resi' => '030071590590',
 *      'status' => 'Delivered',
 *      'service' => 'tiki',
 *      'service_code' => 'ons',
 *      'tanggal' => '17-Jul 14:08',
 *      'dikirim_tanggal' => '',
 *      'estimasi_sampai'=> '',
 *      'pengirim' => '',
 *      'penerima' => '',
 *      'dikirim_dari => '',
 *      'dikirim_ke => ''
 *      'status' => 'Success / RECEIVED BY: HARIS',
 *      'history => [
 *          'tanggal' => '',
 *          'status' => '',
 *          'lokasi' => '',
 *          'keterangan' => '',
 *      ]'
 * ]
 */
$tiki = new Tiki();
$track = $tiki->track('030071590590');
var_dump($track);
```