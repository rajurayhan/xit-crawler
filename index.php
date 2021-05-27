<?php 
    require './vendor/autoload.php';
    require './library/Crawler.php';
    // echo phpinfo();
    use JonnyW\PhantomJs\Client;
    // use DOMDocument;
    // use DOMXPath;

    try {

        $url = 'https://www.jbhifi.com.au/collections/computers-tablets/desktop-all-in-ones';

        $response = Crawler::getPage($url);
        

        if($response->getStatus() === 200) {
            $html =  $response->getContent();

            $dom = new DOMDocument;

            @$dom->loadHTML($html);
            
            $links = $dom->getElementsByTagName('a');

            $products = [];
            $productsUrl = [];
            foreach ($links as $link){
                $url = $link->getAttribute('href');
                if (strpos($url, "products/") == 1) {
                    $productUrl = 'https://www.jbhifi.com.au'.$url;
                    $productsUrl[] = $productUrl;
                }
            }

            // print_r($carsUrl);

            $productResponse = [];
            $productData = [];

            if(isset($productsUrl)){
                $product = [];
                $productResponse = Crawler::getPage($productsUrl[7]);
                if($productResponse->getStatus() === 200) {
                    $productHtml =  $productResponse->getContent();
                    $productHtml = mb_convert_encoding($productHtml, 'HTML-ENTITIES', "UTF-8");                    

                    $productDom = new DOMDocument;
                    @$productDom->loadHTML($productHtml);
                    $xpath = new DOMXPath($productDom);

                    // Product Image
                    $figures = $xpath->query("//figure[@class='slick-slide']//a[1]/@href");
                    if(isset($figures)){
                        $product['image'] = $figures[0]->nodeValue;
                    }

                    // Product Name 
                    $name = $xpath->query("//h1[@itemprop='name']");
                    if(isset($name)){
                        $product['title'] = $name[0]->nodeValue;
                    }

                    // Product description 
                    $descriptions = $xpath->query("//div[@class='descriptions']//p");
                    if(isset($descriptions)){
                        $descriptionText = '<div>';
                        foreach ($descriptions as $key => $description) {
                            $descriptionText .= '<p>'.$description->nodeValue.'</p>';
                        }
                        $descriptionText .= '</div>';

                        $product['description'] = $descriptionText;
                    }

                    // Product Price
                    $prices = $xpath->query("//span[@class='price']");
                    if(isset($prices)){
                        $product['price'] = $prices[0]->nodeValue;
                    }
                    

                    // Product SKU and MModel 
                    $skus = $xpath->query("//dd");
                    if(isset($skus)){
                        $product['model']   = $skus[0]->nodeValue;
                        $product['sku']     = $skus[1]->nodeValue;
                    }
                    
                }
                
                // foreach($productsUrl as $index => $productUrl){
                    
                // }
            }

        }
    } catch (\Throwable $th) {
        //throw $th;
        print_r($th->getMessage());
    }
?>