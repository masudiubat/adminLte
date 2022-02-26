<?php
use App\ReportImage;
/**
* Function for convert short code to image url
*/
if(!function_exists("shortcodet_to_image_url")){
	function shortcodet_to_image_url($contentget)
    {
        $results = preg_match_all("/\[([^\]]*)\]/", $contentget, $matches);

        if ($results === false || $results === 0) {
            return $contentget;
        }

        [$placeholders, $figureids] = $matches;

        $figureArr = array();
        foreach ($figureids as $figure) {
            $code = preg_replace('/<[^>]*>/', '', $figure);
            $figureArr[] = $code;
        }

        $placeHolderArr = array();
        foreach ($placeholders as $placeholder) {
            $placeHolderArr[] = preg_replace('/<[^>]*>/', '', $placeholder);
        }

        $figures = ReportImage::query()
            ->whereIn('code', $figureArr)
            ->get();

        if ($figures->isEmpty()) {
            return $contentget;
        }

        foreach ($placeHolderArr as $index => $placeholder) {
            if ($figures) {
                $content = Str::of('<br/><img src="##URL##" alt="##ALT##" class="img-responsive" height="200px" width="300px"><br/>')
                    ->replace('##URL##', 'http://127.0.0.1:8000/storage/reports/' . $figures[$index]['name'])
                    ->replace('##ALT##', $figures[$index]['alt']);

                $contentget = str_replace($placeholders[$index], $content, $contentget);
            }
        }
        return $contentget;
    }
}


/**
* Function for generate image url for download pdf
*/
if(!function_exists("shortcodet_to_pdf_image_url")){
	function shortcodet_to_pdf_image_url($contentget)
    {
        $results = preg_match_all("/\[([^\]]*)\]/", $contentget, $matches);

        if ($results === false || $results === 0) {
            return $contentget;
        }

        [$placeholders, $figureids] = $matches;

        $figureArr = array();
        foreach ($figureids as $figure) {
            $code = preg_replace('/<[^>]*>/', '', $figure);
            $figureArr[] = $code;
        }

        $placeHolderArr = array();
        foreach ($placeholders as $placeholder) {
            $placeHolderArr[] = preg_replace('/<[^>]*>/', '', $placeholder);
        }

        $figures = ReportImage::query()
            ->whereIn('code', $figureArr)
            ->get();

        if ($figures->isEmpty()) {
            return $contentget;
        }

        foreach ($placeHolderArr as $index => $placeholder) {
            if ($figures) {
                $content = Str::of('<br/><img src="##URL##" alt="##ALT##" class="img-responsive" height="200px" width="250px"><br/>')
                    ->replace('##URL##', '/storage/reports/' . $figures[$index]['name'])
                    ->replace('##ALT##', $figures[$index]['alt']);

                $contentget = str_replace($placeholders[$index], $content, $contentget);
            }
        }
        return $contentget;
    }
}