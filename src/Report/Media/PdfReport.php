<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-10
 * Time: 17:01
 */

namespace Zhyu\Report\Media;


use Zhyu\Report\ReportGenerator;

class PdfReport extends ReportGenerator
{
    public function make()
    {
        $headers = $this->headers;
        $query = $this->query;
        $columns = $this->columns;
        $limit = $this->limit;
        $groupByArr = $this->groupByArr;
        $orientation = $this->orientation;
        $editColumns = $this->editColumns;
        $showTotalColumns = $this->showTotalColumns;
        $styles = $this->styles;
        $showHeader = $this->showHeader;
        $showMeta = $this->showMeta;
        $showNumColumn = $this->showNumColumn;
        $applyFlush = $this->applyFlush;
        if ($this->withoutManipulation) {
            $html = \View::make('zhyu::without-manipulation-pdf-template', compact('headers', 'columns', 'showTotalColumns', 'query', 'limit', 'groupByArr', 'orientation', 'showHeader', 'showMeta', 'applyFlush', 'showNumColumn'))->render();
        } else {
            $html = \View::make('zhyu::general-pdf-template', compact('headers', 'columns', 'editColumns', 'showTotalColumns', 'styles', 'query', 'limit', 'groupByArr', 'orientation', 'showHeader', 'showMeta', 'applyFlush', 'showNumColumn'))->render();
        }
        try {
            $pdf = \App::make('snappy.pdf.wrapper');
            $pdf->setOption('footer-font-size', 10);
            $pdf->setOption('footer-left', 'Page [page] of [topage]');
            $pdf->setOption('footer-right', 'Date Printed: ' . date('d M Y H:i:s'));
        } catch (\ReflectionException $e) {
            try {
                $pdf = \App::make('dompdf.wrapper');
            } catch (\ReflectionException $e) {
                throw new \Exception('Please install either barryvdh/laravel-snappy or laravel-dompdf to generate PDF ZhyuReport!');
            }
        }
        return $pdf->loadHTML($html)->setPaper($this->paper, $orientation);
    }
    public function stream()
    {
        return $this->make()->stream();
    }
    public function download($filename)
    {
        return $this->make()->download($filename . '.pdf');
    }

}