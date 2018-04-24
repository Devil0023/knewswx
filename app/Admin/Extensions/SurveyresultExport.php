<?php
namespace App\Admin\Extensions;
use Encore\Admin\Grid\Exporters\AbstractExporter;


class SurveyresultExport extends AbstractExporter{

    public function export(){

        $filename = $this->getTable().'.csv';
        $titles   = array();

        // 这里获取数据
        $data     = $this->getData();

        if (!empty($data)) {
            $columns = array_dot($this->sanitize($data[0]));
            $titles  = array_keys($columns);
        }

        var_dump($titles);

        foreach($data as $row){
            var_dump($row);  die;
        }


        // 根据上面的数据拼接出导出数据，
        $output = '';

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // 导出文件，
        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;

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