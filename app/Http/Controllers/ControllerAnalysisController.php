<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ControllerAnalysisExport;

class ControllerAnalysisController extends Controller
{
    protected $controllerData = [];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:report-list');
        $this->analyzeControllers();
    }

    public function index()
    {
        return view('reports.controller-analysis', [
            'controllerData' => $this->controllerData
        ]);
    }

    protected function analyzeControllers()
    {
        $controllersPath = app_path('Http/Controllers');
        $files = File::files($controllersPath);

        foreach ($files as $file) {
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if ($className === 'Controller') continue;

            $content = file_get_contents($file->getPathname());

            $this->controllerData[] = [
                'name' => $className,
                'metrics' => $this->calculateMetrics($content),
                'methods' => $this->analyzeMethods($content),
                'file_size' => round(strlen($content) / 1024, 2), // Size in KB
                'last_modified' => date("Y-m-d H:i:s", filemtime($file->getPathname())),
                'permissions' => $this->extractPermissions($content),
                'dependencies' => $this->extractDependencies($content),
            ];
        }
    }

    protected function calculateMetrics($content)
    {
        return [
            'total_lines' => substr_count($content, "\n") + 1,
            'code_lines' => substr_count($content, ";"),
            'methods_count' => substr_count($content, "function"),
            'complexity' => $this->calculateComplexity($content),
        ];
    }

    protected function analyzeMethods($content)
    {
        preg_match_all('/public function ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\([^)]*\)/', $content, $matches);

        $methods = [];
        foreach ($matches[1] as $method) {
            $methods[] = [
                'name' => $method,
                'type' => $this->determineMethodType($method)
            ];
        }

        return $methods;
    }

    protected function determineMethodType($methodName)
    {
        $crudMethods = [
            'index' => 'List/View',
            'create' => 'Create Form',
            'store' => 'Create Action',
            'show' => 'View Detail',
            'edit' => 'Edit Form',
            'update' => 'Update Action',
            'destroy' => 'Delete Action'
        ];

        return $crudMethods[$methodName] ?? 'Custom Action';
    }

    protected function calculateComplexity($content)
    {
        $complexity = 0;
        $complexity += substr_count($content, "if");
        $complexity += substr_count($content, "foreach");
        $complexity += substr_count($content, "while");
        $complexity += substr_count($content, "case");
        $complexity += substr_count($content, "&&");
        $complexity += substr_count($content, "||");
        return $complexity;
    }

    protected function extractPermissions($content)
    {
        preg_match_all("/permission:['\"]([^'\"]+)['\"]/", $content, $matches);
        return array_unique($matches[1]);
    }

    protected function extractDependencies($content)
    {
        preg_match_all('/use ([^;]+);/', $content, $matches);
        return array_unique($matches[1]);
    }

    public function downloadPdf()
    {
        $pdf = PDF::loadView('reports.controller-analysis-pdf', [
            'controllerData' => $this->controllerData
        ]);

        return $pdf->download('controller-analysis-report.pdf');
    }

    public function downloadExcel()
    {
        return Excel::download(new ControllerAnalysisExport($this->controllerData), 'controller-analysis-report.xlsx');
    }

    public function downloadWord()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Add title
        $section->addText('Controller Analysis Report', ['bold' => true, 'size' => 16]);
        $section->addTextBreak();

        foreach ($this->controllerData as $controller) {
            // Controller name as heading
            $section->addText($controller['name'], ['bold' => true, 'size' => 14]);

            // Metrics table
            $table = $section->addTable();
            $table->addRow();
            $table->addCell(2000)->addText('Metric');
            $table->addCell(2000)->addText('Value');

            foreach ($controller['metrics'] as $metric => $value) {
                $table->addRow();
                $table->addCell(2000)->addText(ucwords(str_replace('_', ' ', $metric)));
                $table->addCell(2000)->addText((string)$value);
            }

            $section->addTextBreak();
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = 'controller-analysis-report.docx';
        $tempFile = storage_path('app/public/' . $fileName);
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
