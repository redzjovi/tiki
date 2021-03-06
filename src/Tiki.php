<?php

namespace redzjovi\tiki;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class Tiki
{
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
     *      'dikirim_tanggal_format' => '2017-07-14',
     *      'estimasi_sampai'=> '15 July, 2017',
     *      'estimasi_sampai_format'=> '2017-07-15',
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
    public function track($resi = 0)
    {
        $result = [];

        $client = new Client();
        $response = $client->request('GET', 'https://tiki.id/resi/'.$resi);
        $html = $response->getBody();

        $result = $this->parseDom($html);
        return $result;
    }

    public function parseDom($html)
    {
        $domHtml = HtmlDomParser::str_get_html($html);

        $dom = $domHtml;
        $dom = $dom->find('div[id=heading0] div span');
        if (! isset($dom[1])) {
            return false;
        }
        $dom = $dom[1]->innertext;
        if ($dom == 'Tidak ada tracking') {
            return false;
        }

        $dom = $domHtml;
        $dom = $dom->find('div[id=heading0] div h4');
        $dom = $dom[0]->innertext;
        $dom = explode('&nbsp;', $dom);
        $dom = trim(reset($dom));
        $result['resi'] = $dom;

        $result['status'] = '';
        $result['service'] = 'tiki';

        $dom = $domHtml;
        $dom = $dom->find('div[id=heading0] div h4 span');
        $dom = $dom[0]->innertext;
        $result['service_code'] = $dom;

        $dom = $domHtml;
        $dom = $dom->find('div[id=heading0] div span[class=pull-right hidden-xs hidden-sm] small');
        $dom = $dom[0]->innertext;
        $dom = explode('</i>', $dom);
        $dom = trim(end($dom));
        $result['tanggal'] = $dom;

        $dom = $domHtml;
        $dom = $dom->find('div[id=collapse0] div table tbody tr');
        $dom = $dom[1]->find('td');
        $dikirim_tanggal = $dom[0]->innertext;
        $dikirim_tanggal = explode('</strong>', $dikirim_tanggal);
        $dikirim_tanggal = trim(end($dikirim_tanggal));
        $result['dikirim_tanggal'] = $dikirim_tanggal;
        $result['dikirim_tanggal_format'] = Carbon::createFromFormat('d F, Y', $dikirim_tanggal)->toDateString();
        $estimasi_sampai = $dom[1]->innertext;
        $estimasi_sampai = explode('</strong>', $estimasi_sampai);
        $estimasi_sampai = trim(end($estimasi_sampai));
        $result['estimasi_sampai'] = $estimasi_sampai;
        $result['estimasi_sampai_format'] = Carbon::createFromFormat('d F, Y', $estimasi_sampai)->toDateString();

        $dom = $domHtml;
        $dom = $dom->find('div[id=collapse0] div table tbody tr td b');
        $result['pengirim'] = $dom[0]->innertext;
        $result['penerima'] = $dom[1]->innertext;

        $dom = $domHtml;
        $dom = $dom->find('div[id=collapse0] div table tbody tr td small');
        $result['dikirim_dari'] = $dom[0]->innertext;
        $result['dikirim_ke'] = $dom[1]->innertext;

        $dom = $domHtml;
        $dom = $dom->find('div[id=heading0] div span');
        $dom = $dom[2]->innertext;
        $dom = explode('&nbsp;', $dom);
        $dom = $dom[2];
        $dom = explode('<small', $dom);
        $dom = trim($dom[0]);
        $result['status'] = $dom;

        $result['history'] = [];
        $dom = $domHtml;
        $dom = $dom->find('ul[class=timeline] li');
        $i = 0;
        foreach ((array) $dom as $li) {
            $dom = $li->find('div[class=timeline-body] small');
            $dom = $dom[0]->innertext;
            $dom = explode('</i>', $dom);
            $dom = trim(end($dom));
            $result['history'][$i]['tanggal'] = $dom;

            $dom = $li->find('div[class=timeline-body] p');
            $dom = $dom[0]->innertext;
            $dom = trim($dom);
            $result['history'][$i]['status'] = $dom;

            $dom = $li->find('div[class=timeline-heading] h4');
            $dom = $dom[0]->innertext;
            $dom = trim($dom);
            $result['history'][$i]['lokasi'] = $dom;

            $result['history'][$i]['keterangan'] = '';
            $i++;
        }

        return $result;
    }
}
