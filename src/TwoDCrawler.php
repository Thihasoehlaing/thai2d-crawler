<?php

namespace Thihasoehlaing\TwoDCrawler;

use Carbon\Carbon;
use DOMDocument;
use DOMXPath;

class TwoDCrawler
{
    protected $set;
    protected $value;
    protected $time;

    public function __construct()
    {
        [$this->set, $this->value, $this->time] = $this->getDataFromSite();
    }

    public function getSet(): string
    {
        return $this->set;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getNumber(): string
    {
        $first = substr($this->set, -1);
        $last_raw_2 = explode('.', $this->value);
        $last = substr($last_raw_2[0], -1);
        $number = $first . $last;

        return $number;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    private function getDataFromSite(): array
    {
        $site = file_get_contents("https://www.set.or.th/en/market/product/stock/overview");
        preg_match('/Last Update (\d{2} [A-Za-z]{3} \d{4} \d{2}:\d{2}:\d{2})/', $site, $LastUpdateMatche);
        if($LastUpdateMatche) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true); // Disable error reporting for malformed HTML
            $dom->loadHTML($site);
            libxml_use_internal_errors(false); // Enable error reporting

            $xpath = new DOMXPath($dom);

            $div = $xpath->query('//div[@no-collapse][contains(concat(" ", normalize-space(@class), " "), " table-index-overview ")]');

            if ($div->length > 0) {
                $element = $div->item(0);
                $table = $xpath->query('.//table[contains(concat(" ", normalize-space(@class), " "), " table-custom-field--cnc ")]', $element);

                if ($table->length > 0) {
                    $tbody = $xpath->query('.//tbody', $table->item(0));
                    if ($tbody->length > 0) {
                        $tr = $xpath->query('.//tr[1]', $tbody->item(0));

                        if ($tr->length > 0) {
                            $td2 = $xpath->query('.//td[@aria-colindex="2"]', $tr->item(0));
                            $td8 = $xpath->query('.//td[@aria-colindex="8"]', $tr->item(0));

                            if ($td2->length > 0 && $td8->length > 0) {
                                $set = trim($td2->item(0)->nodeValue);
                                $value = trim($td8->item(0)->nodeValue);

                                $currentYear = date('Y');
                                $array = explode($currentYear ." ", $LastUpdateMatche[1]);
                                $bangkokTime = $array[1];
                                $carbonBangkok = Carbon::createFromFormat('H:i:s', $bangkokTime, 'Asia/Bangkok');
                                $carbonRangoon = $carbonBangkok->copy()->setTimezone('Asia/Rangoon');
                                $time = $carbonRangoon->format('H:i:s');

                                return [$set, $value, $time];
                            }
                        }
                    }
                }
            }
        }
    }
}
