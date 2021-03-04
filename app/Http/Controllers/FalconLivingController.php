<?php

namespace App\Http\Controllers;
use PHPHtmlParser\Dom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
class FalconLivingController extends Controller
{
    private $dom  = '';
    private $data = array();
    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('mysql.connect_timeout', '0');
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        $this->dom = new Dom;
    }
    public function index()
    {
        $data     = array();
        $response = Http::get('https://www.falconliving.com/property/MRD10665165/?cc=true');
        $page     = $response->getBody();
        $this->dom->load($page);
        self::detailSectionData();
        $this->data['contact_us'] = $this->dom->find('div[class="callus-menu"]')->find('a',0)->href;
        $this->data['contact_us'] = str_replace('tel:','',$this->data['contact_us']);
        $this->data['property_description'] = $this->dom->find('div[class="additional-information-element"]')->find('p',0)->text;
        self::writeData();
    }
    private function writeData()
    {
        Storage::disk('public')->put('falconliving.json', json_encode($this->data));
    }
    private function detailSectionData()
    {
        $columns = array();
        $counter = 0;
        $tables  = $this->dom->find('dl[class="property-details-section"]');
        foreach($tables as $table)
        {
            foreach($table->find('dt') as $row)
            {
                $columns[] = self::removeSpecialCharacter($row->text);
            }
            foreach($table->find('dd') as $row)
            {
                $this->data[$columns[$counter]] = $row->text;
                $counter++;
            }
        }
    }
    private function removeSpecialCharacter($column)
    {
        $column = str_replace(' ','_',$column);
        $column = preg_replace('/[^A-Za-z0-9\_]/', '', $column);
        return strtolower($column);
    }
};
