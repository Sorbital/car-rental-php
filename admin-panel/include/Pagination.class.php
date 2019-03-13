<?php
	/*
	 * Pagination
	 * v1 - generates basic pagination markup
	 */
	class Pagination{
		private $pageArr = array();
		
		function generatePagination($link, $pageNo, $totalRows, $linkParam){
			// $link = 'index.php';

			//pagination code
			$adjacents = 1;

			/* Setup vars for query. */
			 //your file name  (the name of this file)
			$limit = 10; 								//how many items to show per page
			// $page = $_GET['page'];
			if(isset($pageNo)) {
				$page = $pageNo;
				$start = ($page - 1) * $limit; 			//first item to display on this page
			}
			else {
				$start = 0;								//if no page var is given, set start to 0
				$page = 0;
			}

			/* Get data. */

			// == CHECK IF USER IS SEARCHING ==

			// $countSQL = "SELECT COUNT(*) as num FROM ".PREFIX."table";
			// $resultSQL = "SELECT * FROM ".PREFIX."table order by created DESC LIMIT $start, $limit";
			// $resultSQL = $resultSQL." LIMIT $start, $limit";

			$link_params = $linkParam;
			/* 
			   First get total number of rows in data table. 
			   If you have a WHERE clause in your query, make sure you mirror it here.
			*/
			// $total_pages = $admin->fetch($admin->query($countSQL));
			// $total_pages = $total_pages['num'];
			$total_pages = $totalRows;
			// $result = $admin->query($resultSQL);

			/* Setup page vars for display. */
			if ($page == 0) $page = 1;					//if no page var is given, default to 1.
			$prev = $page - 1;							//previous page is page - 1
			$next = $page + 1;							//next page is page + 1
			$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;						//last page minus 1

			/* 
				Now we apply our rules and draw the pagination object. 
				We're actually saving the code to a variable in case we want to draw it more than once.
			*/
			$pagination = "";
			if($lastpage > 1)
			{
				$pagination .= "<ul class=\"pagination\">";
				//previous button
				if ($page > 1) 
					$pagination.= "<li><a href=\"$link?page=$prev&$link_params\"><span aria-hidden=\"true\">&laquo;</span> <span class=\"hidden-xs\">Previous</span></a></li>";
				else
					$pagination.= "<li class=\"disabled\"><a href=\"#\"><span aria-hidden=\"true\">&laquo;</span> <span class=\"hidden-xs\">Previous</span></a></li>";

				//pages	
				if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
				{
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
						else
							$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$counter&$link_params\">$counter</a></li>";
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
				{
					//close to beginning; only hide later pages
					if($page < 1 + ($adjacents * 2))		
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<li class=\"active\"><span class=\"current\">$counter</span>";
							else
								$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$counter&$link_params\">$counter</a></li>";
						}
						$pagination.= "<li class=\"disabled\"><a href=\"\">...</a></li>";
						$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$lpm1&$link_params\">$lpm1</a></li>";
						$pagination.= "<li><a href=\"$link?page=$lastpage&$link_params\">$lastpage</a></li>";
					}
					//in middle; hide some front and some back
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= "<li><a href=\"$link?page=1&$link_params\">1</a></li>";
						$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=2&$link_params\">2</a></li>";
						$pagination.= "<li class=\"disabled\"><a href=\"\">...</a></li>";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<li class=\"active\"><span class=\"current\">$counter</span></li>";
							else
								$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$counter&$link_params\">$counter</a></li>";
						}
						$pagination.= "<li class=\"disabled\"><a href=\"\">...</a></li>";
						$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$lpm1&$link_params\">$lpm1</a></li>";
						$pagination.= "<li><a href=\"$link?page=$lastpage&$link_params\">$lastpage</a></li>";
					}
					//close to end; only hide early pages
					else
					{
						$pagination.= "<li><a href=\"$link?page=1&$link_params\">1</a></li>";
						$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=2&$link_params\">2</a></li>";
						$pagination.= "<li class=\"disabled\"><a href=\"\">...</a></li>";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<li class=\"active\"><span class=\"current\">$counter</span></li>";
							else
								$pagination.= "<li class=\"hidden-xs\"><a href=\"$link?page=$counter&$link_params\">$counter</a></li>";
						}
					}
				}

				//next button
				if ($page < $counter - 1) 
					$pagination.= "<li><a href=\"$link?page=$next&$link_params\"><span class=\"hidden-xs\">Next</span> <span aria-hidden=\"true\">&raquo;</span></a></li>";
				else
					$pagination.= "<li class=\"disabled\"><span class=\"hidden-xs\">Next</span> <span aria-hidden=\"true\">&raquo;</span></li>";
				$pagination.= "</ul><br/><br/>";
			}

			$this->pageArr['start'] = $start;
			$this->pageArr['limit'] = $limit;
			$this->pageArr['paginationHTML'] = $pagination;
			return $this->pageArr;
		}
	}
?>