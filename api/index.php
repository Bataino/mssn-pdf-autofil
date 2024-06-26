<?php
function output($pd)
{
    // Output the new PDF
    ob_end_clean(); //    the buffer and never prints or returns anything.
    ob_start();
    $pd->Output("D", $_POST["name"] . ".pdf");
    ob_end_flush();
}


error_reporting(1);
// error_reporting(E_ERROR | E_PARSE);
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 

ob_start();
require_once __DIR__ . '/../vendor/autoload.php';

use setasign\Fpdi\Fpdi;


$data = [
    "Personality_finance_letter.pdf" => ["x" => 20, "y" => 30],
    "Non-Muslim Brand.pdf" => ["x" => 20, "y" => 30],
    "Muslim Politicians.pdf" => ["x" => 20, "y" => 30],
    "Non-Muslim Politicians.pdf" => ["x" => 20, "y" => 30],
    "Muslim Brand.pdf" => ["x" => 20, "y" => 25, "x_date" => 10, "y_date" => 2],
];

if ($_POST) {

    $pdf = new FPDI();
    extract($_POST);


    $fileSpec = json_decode($fileSpec, true);
    $pageCount = $pdf->setSourceFile(__DIR__ . '/../files/' . $fileSpec["name"]);

    // iterate through all pages
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        // import a page
        $templateId = $pdf->importPage($pageNo);
        // get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);
        $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

        // use the imported page
        $pdf->useTemplate($templateId);
        $pdf->SetFont('Helvetica');
        $pdf->SetXY(5, 5);

        //Add some text to only a specific page
        if ($pageNo == 1) {

            $pdf->SetXY($fileSpec["x"], $fileSpec["y"]);
            $pdf->Write(138, $_POST["name"]);

            $y_date = 20;
            $x_h_date = 35;
            $x_g_date = 140;

            // Update For Hij Calendar
            $pdf->SetXY($x_h_date, $y_date);
            $pdf->Write(138, $_POST["h_date"]);

            // Update for Geg Calendar
            $pdf->SetXY($x_g_date, $y_date);
            $pdf->Write(137, $_POST["g_date"]);
        }
    }


    output($pdf);

}


?>
<html>

<head>
    <title>
        MSSN Title
    </title>
</head>

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <div class="mx-auto d-flex">
        <form action="." method="post" class="text-center p-3 p-sm-5 p-4 mx-auto">
            <input type="text" class="form-control p-2 border my-3" name="name" placeholder="Brand or Individual Name"
                required />
            <input type="text" class="form-control p-2 border my-3" name="g_date" placeholder="Gregorian Date"
                required />
            <input type="text" class="form-control p-2 border my-3" name="h_date" placeholder="Hijra Date" required />
            <select name="fileSpec" required class="form-control p-2 border">
                <option selected disabled>Select File</option>
                <?php
                foreach ($data as $value => $k) {
                    ?>
                    <option value='<?php echo json_encode(array_merge($k, ["name" => $value])); ?>'> <?php echo $value; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <button type="submit" value="true" name="submit" class="btn btn-success mt-3">Submit</button>
        </form>
    </div>
</body>

</html>