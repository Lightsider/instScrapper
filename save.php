<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(!empty($_POST["issubmit"]))
{
    include "config.php";
    $countUsers=0;
    for($i=1;$i<=$userPerPage;$i++)
    {
        if(!empty($_POST['user'.$i]))
        {

            $username = $_POST['username'.$i];
            $fullname = $_POST['fullname'.$i];
            $categories = "";
            for($j=1;$j<=$userPerPage;$j++) {
                if(!empty($_POST['category'.$i.'-'.$j])) {
                    $categories .= $_POST['category'.$i.'-'.$j] . ",";
                }
            }
            if($categories!=="")
                $categories = substr($categories, 0, -1);
            $email = $_POST['email'.$i];
            $location = $_POST['location'.$i];

            $photos = "";
            for($j=0;$j<$userPerPage;$j++) {
                if(!empty($_POST['media'.$i.'-'.$j])) {
                    $photos .= $_POST['media'.$i.'-'.$j] . ",";
                }
            }
            if($photos!=="")
                $photos = substr($photos, 0, -1);
            try {
                $reader = new Xlsx();
                $spreadsheet = $reader->load($outputFileName);
                $spreadsheet->setActiveSheetIndex(0);

                $cells = $spreadsheet->getActiveSheet()->getCellCollection();
                $worksheet = $spreadsheet->getActiveSheet();

                $row = $cells->getHighestRow() + 1;

                $worksheet->setCellValue("A" . $row, $fullname);
                $worksheet->setCellValue("B" . $row, $username);
                $worksheet->setCellValue("C" . $row, $categories);
                $worksheet->setCellValue("D" . $row, $email);
                $worksheet->setCellValue("E" . $row, $location);
                $worksheet->setCellValue("F" . $row, $photos);

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($outputFileName);

                $countUsers++;
            }
            catch (Exception $e)
            {
                echo 'Something went wrong: ' . $e->getMessage() . "<br>";
            }
        }
    }
    if(isset($_GET['page']))
        header("Location: scrapper.php?page=".(htmlspecialchars($_GET['page']+1))."&message=You've saved ".$countUsers." record in your excel file.");
    else
        header("Location: scrapper.php");

}
else die("form isn't submit");