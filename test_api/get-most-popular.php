
<?php 
 	set_time_limit(120);
	include('simple_html_dom.php');

	$url = "http://interview.funplay8.com/";

		$html = file_get_html($url);

		$pages = array();

		foreach($html->find('.pagination') as $page)
		{
			foreach($page->find('a') as $a)
			{
				$pages[] = $a->attr['href'];
			}
			$filter_pages = array_slice($pages, 1, -1);
			// print_r($pages);
		}

		$product_array = array();

		$id=1;

		$pageNumber = 1;

		$requestCount = 81;

		foreach($filter_pages as $indiviPage)
		{
			$indiviUrl = "http://interview.funplay8.com/" .$indiviPage;

			$html = file_get_html($indiviUrl);

			foreach($html->find('.well') as $div)
			{
				foreach($div->find('.col-sm-4') as $col)
				{
					foreach($col->find('.meme-img') as $img)
					{
						$imageUrl = $img->attr['src'];
						// echo $imageUrl ."<br>";
					}

					foreach($col->find('h6') as $text)
					{
						$name = $text->plaintext;
						// echo $name ."<br>";
					}
					
					if($requestCount == 0 ){

						$product_array[] = ([
							"id" => $id,
							"name" => $name,
							"url" => $imageUrl,
							"page" => $pageNumber,
							"requestCount" => 0
						]);

						$id++;
					} else{

						$product_array[] = ([
							"id" => $id,
							"name" => $name,
							"url" => $imageUrl,
							"page" => $pageNumber,
							"requestCount" => $requestCount
						]);

						$id++;
						$requestCount--;
					}
				}		
			}

			$pageNumber++;
		}

		$most_number = 0;

		foreach($product_array as $data)
		{
			if($data['requestCount'] > $most_number)
			{
				$most_number = $data['requestCount'];
				$most_item = $data;
			}
		} 
		$json_result = json_encode($most_item);
		print_r($json_result);
?>
		
	
