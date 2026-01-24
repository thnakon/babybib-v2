<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title }} - {{ $bibliographyTitle }}</title>
    <style>
        /* PDF specific: Sarabun from Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');

        /* Default Font Styles for Word and PDF */
        body {
            font-family: 'Angsana New', 'Sarabun', 'TH Sarabun New', sans-serif;
            line-height: 1.2;
            color: #000;
            margin: 40px;
        }

        h1 {
            text-align: center;
            font-size: 18pt !important;
            margin-bottom: 20pt;
            text-transform: capitalize;
            font-weight: bold;
            font-family: 'Angsana New', 'Sarabun', sans-serif;
        }

        .bibliography-list {
            margin-top: 20pt;
        }

        .reference-item {
            margin-bottom: 10pt;
            padding-left: 36pt;
            text-indent: -36pt;
            font-size: 16pt !important;
            font-family: 'Angsana New', 'Sarabun', sans-serif;
            line-height: 1.5;
        }

        .reference-item em {
            font-style: italic;
        }

        /* DomPDF Specific - help with Thai character rendering */
        @media print {
            body {
                font-family: 'Sarabun', sans-serif;
                font-size: 16pt;
            }

            h1 {
                font-size: 18pt;
            }

            .reference-item {
                font-size: 16pt;
            }
        }
    </style>
</head>

<body>
    <h1>{{ $bibliographyTitle }}</h1>

    <div class="bibliography-list">
        @foreach ($references as $reference)
            <div class="reference-item">
                {!! $reference->formatted_citation !!}
            </div>
        @endforeach
    </div>
</body>

</html>
