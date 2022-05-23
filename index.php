<?php
if (!empty($_REQUEST['Sender'])):
    $sender = $_REQUEST['Sender'];
    $layout = file_get_contents('./layout.html', FILE_USE_INCLUDE_PATH);

    foreach ($sender as $key => $value) {
        $key = strtoupper($key);
        $start_if = strpos($layout, '[[IF-' . $key . ']]');
        $end_if = strpos($layout, '[[ENDIF-' . $key . ']]');
        $length = strlen('[[ENDIF-' . $key . ']]');

        if (!empty($value)) {
            // Add the value at its proper location.
            $layout = str_replace('[[IF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[ENDIF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[' . $key . ']]', $value, $layout);
        } elseif (is_numeric($start_if)) {
            // Remove the placeholder and brackets if there is an if-statement but no value.
            $layout = str_replace(substr($layout, $start_if, $end_if - $start_if + $length), '', $layout);
        } else {
            // Remove the placeholder if there is no value.
            $layout = str_replace('[[' . $key . ']]', '', $layout);
        }
    }

    // Clean up any leftover placeholders. This is useful for booleans,
    // which are not submitted if left unchecked.
    $layout = preg_replace("/\[\[IF-(.*?)\]\]([\s\S]*?)\[\[ENDIF-(.*?)\]\]/u", "", $layout);

    if (!empty($_REQUEST['download'])) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename=signature.html');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

    echo $layout;
else: ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Lukas Heuberger">
    <!-- <link rel="shortcut icon" type="image/png" href="assets/favicon.ico"/> -->

    <title>PFF Signature Generator</title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style type="text/css">
        /* Sticky footer styles
        -------------------------------------------------- */

        html,
        body {
            height: 100%;
            /* The html and body elements cannot have any padding or margin. */
        }

        /* Wrapper for page content to push down footer */
        #wrap {
            min-height: 100%;
            height: auto !important;
            height: 100%;
            /* Negative indent footer by its height */
            margin: 0 auto -60px;
            /* Pad bottom by footer height */
            padding: 0 0 60px;
        }

        /* Set the fixed height of the footer here */
        #footer {
            height: 60px;
            background-color: #f5f5f5;
        }

        /* Custom page CSS
        -------------------------------------------------- */
        /* Not required for template or sticky footer method. */

        #wrap > .container {
            padding: 60px 15px 0;
        }

        .container .credit {
            margin: 20px 0;
        }

        #footer > .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        code {
            font-size: 80%;
        }

        .navbar-default {
            background-color: #ffffff;

        }
    </style>

</head>

<body>

<!-- Wrap all page content here -->
<div id="wrap" class="pinnacle-wrapper">
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                <!-- <img src="assets/Logo_Pfadi_TG_CMYK.png" height="40" alt="PTG Logo"/> -->
                PFF 23 Signature Generator
                </a>
            </div>
        </div>
    </div>
    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1 class="main-header"> PFF 23 Signature Generator</h1>
        </div>
        <form role="form" method="post" target="preview" id="form">
            <div class="row">
                <!-- Personal information -->
                <div class="col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="Name">Vorname</label>
                        <input type="text" class="form-control" id="Surname" name="Sender[surname]"
                               placeholder="Gib deinen Vornamen ein">
                    </div>
                    <div class="form-group">
                        <label for="Name">Nachname</label>
                        <input type="text" class="form-control" id="Name" name="Sender[name]"
                               placeholder="Gib deinen Nachnamen ein">
                    </div>
                    <div class="form-group">
                        <label for="Name">Pfadiname</label>
                        <input type="text" class="form-control" id="Pfadiname" name="Sender[pfadiname]"
                               placeholder="Gib deinen Pfadinamen ein">
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email" name="Sender[email]"
                               placeholder="Enter your email" value = "@pfadi-thurgau.ch">
                    </div>
                  -->
                    <div class="form-group">
                        <label for="Phone">Telefonnummer (Format: +41 79 123 45 67)</label>
                        <input type="phone" class="form-control phone" id="personalPhone" name="Sender[phone]"
                               placeholder="bitte gib deine Handynummer ein. Format: +41 79 123 45 67" value = "+41 7">
                    </div>
                </div>

                <!--PTG Information -->
                <div class="col-sm-6 col-md-6">

                    <div class="form-group">
                        <label for="Ressort"> Ressort </label>
                        <select id="ressort" name="Sender[ressort]">
                            <option value="Finanzen">Finanzen</option>
                            <option value="Kommunikation">Kommunikation</option>
                            <option value="Bühnenprogramm">Bühnenprogramm</option>
                            <option value="Rahmenprogramm">Rahmenprogramm</option>
                            <option value="Infrastruktur">Infrastruktur</option>
                            <option value="Food & Beverage">Food & Beverage</option>
                            <option value="Staff">Staff</option>
                            <option value="Sicherheit">Sicherheit</option>
                            <option value=" ">keine</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="function"> Funktion </label>
                        <select id="function" name="Sender[function]">
                            <option value="Ressortleitung">Ressortleitung</option>
                            <option value="Unterressortleitung">Unterressortleitung</option>
                            <option value="Sekretariat">Sekretariat</option>
                            <option value="Präsident">Präsident</option>
                            <option value="Präsidentin">Präsidentin</option>
                        </select>
                    </div>
                </div>

            <!--action buttons -->
            <button id="preview" type="submit" class="btn btn-default">Preview</button>
            <!-- <button id="download" class="btn btn-default">Download</button> -->
            <!-- <input type="hidden" name="download" id="will-download" value=""> -->
        </form>
    </div>
    <div class="container">
        <!-- preview box -->
        <iframe src="about:blank" name="preview" width="100%" height="500"></iframe>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        // $("#download").bind("click", function () {
        //     $('#will-download').val('true');
        //     $('#form').removeAttr('target').submit();
        // });

        $("#preview").bind("click", function () {
            $('#will-download').val('');
            $('#form').attr('target', 'preview');
        });

        /*  Phone Number Masking */
        $("input.phone").keyup(function () {
            $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1) $2-$3"));
        });

    });

</script>
</body>
</html>
<?php endif;
