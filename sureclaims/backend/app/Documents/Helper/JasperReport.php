<?php


namespace App\Documents\Helper;


use JavaClass;
use Java;
use Response;

header("Content-Type: text/html; charset=utf-8");
class JasperReport
{
    public function checkJavaExtension()
    {
        if (!extension_loaded('java')) {
            $sapi_type = php_sapi_name();
            $port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT']) > 1024)) ? $_SERVER['SERVER_PORT'] : '8080';
            if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli") {
                // this is from ENV config env('TOMCAT_JAVA')
                require_once(env('TOMCAT_JAVA'));
                return true;
            } else {
                // this is from ENV config env('TOMCAT_JAVA')
                if (!(@require_once(env('TOMCAT_JAVA')))) {
                    // this is from ENV config env('TOMCAT_JAVA')
                    require_once(env('TOMCAT_JAVA'));
                }
            }
        }
        if (!function_exists("java_get_server_name")) {
            return "The loaded java extension is not the PHP/Java Bridge";
        }
        return true;
    }


    public function showReport($template_name, $parameters, $tableData = array(), $repFormat = 'pdf')
    {

        $x = $this->checkJavaExtension();
        $report = $template_name;
        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
        $compileManager->__client->cancelProxyCreationTag = 0;

        // this is from ENV config env('TOMCAT_RESOURCE')
        $report = $compileManager->compileReport(realpath(env('TOMCAT_RESOURCE') . $report . '.jrxml'));
        java_set_file_encoding("UTF-8");
        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
        $params = new Java("java.util.HashMap");
        $start = microtime(true);

        #------------- DATA -------------------------------------------------------------------------------------
        #------------- DATA -------------------------------------------------------------------------------------

        foreach ($parameters as $key => $value) {
            $params->put($key, $value);
        }

        $data = $tableData;

        #------------- DATA -------------------------------------------------------------------------------------
        #------------- DATA -------------------------------------------------------------------------------------
        $jCollection = new Java("java.util.ArrayList");
        foreach ($data as $i => $row) {
            $jMap = new Java('java.util.HashMap');
            foreach ($row as $field => $value) {
                $jMap->put($field, $value);
            }
            $jCollection->add($jMap);
        }

        $jMapCollectionDataSource = new Java("net.sf.jasperreports.engine.data.JRMapCollectionDataSource", $jCollection);
        $jasperPrint = $fillManager->fillReport($report, $params, $jMapCollectionDataSource);
        $end = microtime(true);

        $outputPath = tempnam(sys_get_temp_dir(), 'jrx');
        chmod($outputPath, 0777);

        if (strtoupper($repFormat) == 'PDF') {
            header("Content-Type: application/pdf");
            $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
            $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
        } else {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=output.xls");
            $exportManager = new java("net.sf.jasperreports.engine.export.JRXlsExporter");
            $exportManager->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT, $jasperPrint);
            $exportManager->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME, $outputPath);
            $exportManager->exportReport();
        }

        readfile($outputPath);
        unlink($outputPath);
    }
}