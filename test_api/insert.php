
<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
</head>
<body>
	<div class="container">
		<form method="POST">
			<h6>INSERT NEW IMAGES</h6>
				<div class="form-group">
		            <label for="image" class="control-label mb-1 font-weight-bold">Image(s)</label>
		            <table id="item_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		              <tr>
		                <th>Name</th>
		                <th>Public Url</th>
		                <th>Action</th>
		              </tr>
		              <tr>
		                <td><input type="text" name="name[]" class="form-control form-control-inline" required /></td>
		                <td><input type="text" name="url[]" class="form-control form-control-inline" required /></td>
		                <td><button type="button" name="remove" class="btn remove"><span class="input-group-text"><i class="fa fa-times " aria-hidden="true"></i></span></button></td>
		              </tr>
		            </table>
		            <button type="button" name="add" class="btn btn-outline-success add">Add Item</button>
		          </div>
				  	<input type="submit" class="btn btn-primary" value="Submit">
				
		</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.add', function(){
				var html ='';
				html += '<tr>';
	          	html += '<td><input type="text" name="name[]" class="form-control form-control-inline" required/></td>';
	          	html += '<td><input type="text" name="url[]" class="form-control form-control-inline" required/></td>';
	          	html += '<td><button type="button" name="remove" class="btn remove"><span class="input-group-text"><i class="fa fa-times" aria-hidden="true"></i></span></button></td></tr>';
	          	$('#item_table').append(html);
			});

			$(document).on('click', '.remove', function(){
          		$(this).closest('tr').remove();
        	});
		});
	</script>

		

<?php if(isset($_POST['name'])) { 
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


	$id = array_column($product_array, 'id');
		$endid = end($id); //852
		
		$insert_array = array();

		for($i=0; $i<count($_POST['name']); $i++)
		{
			$page = array_column($product_array, 'page');
			$endpage = end($page); //95

			$numItemInPage = array_count_values(array_column($product_array, 'page'))[$endpage];
			// echo $numItemInPage; //6
			// print_r($page);

			$endid++;

			if($numItemInPage != 9)
			{
				$product_array[] = ([
					"id" => $endid,
					"name" => $_POST['name'][$i],
					"url" => $_POST['url'][$i],
					"page" => $endpage,
					"requestCount" => 0
				]);
				
			} else{
				$endpage++;

				$product_array[] = ([
					"id" => $endid,
					"name" => $_POST['name'][$i],
					"url" => $_POST['url'][$i],
					"page" => $endpage,
					"requestCount" => 0
				]);
			}	
		}
		$json_result = json_encode($product_array);
		print_r($json_result);

} ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>