<?php
namespace App\Admin\Extensions;
use Encore\Admin\Grid\Exporters\AbstractExporter;


class SurveyresultExport extends AbstractExporter{

    public function export(){

        $filename = $this->getTable().'.csv';
        $output   = "";

        // 这里获取数据
        $data     = $this->getData();

        foreach($data as $key => $row){
            $info = json_decode($row, true);

            if($key === 0){
                $questions = $this->dealArrStr(array_keys($info));
                $output   .= "Created_at,".implode(",", $questions).PHP_EOL;
            }

            $output .= $row["created_at"].",".implode(",", $this->dealArrStr($info)).PHP_EOL;
        }


        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // 导出文件，
        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;

    }

    private function dealArrStr($array){
        foreach($array as $key => $string){
            $array[$key] = '"'.str_replace('"', '""', str_replace(',', '","', $string)).'"';
        }

        return $array;
    }

    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val) {
            return is_array($val) && !Arr::isAssoc($val);
        })->toArray();
    }

    /**
     * @param $row
     * @param string $fd
     * @param string $quot
     * @return string
     */
    protected static function putcsv($row, $fd = ',', $quot = '"')
    {
        $str = '';
        foreach ($row as $cell) {
            $cell = str_replace([$quot, "\n"], [$quot . $quot, ''], $cell);
            if (strchr($cell, $fd) !== FALSE || strchr($cell, $quot) !== FALSE) {
                $str .= $quot . $cell . $quot . $fd;
            } else {
                $str .= $cell . $fd;
            }
        }
        return substr($str, 0, -1) . "\n";
    }
}

?>