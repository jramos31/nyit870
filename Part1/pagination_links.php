<?php // *******  Pagination Links - Create the links to the other pages     *****************

function show_page_links($pages, $display, $start, $id, $script_name) {

		if ($pages>1) {

			echo '<p>';

			// Determine which page the script currently is
			$current_page = ($start/$display) + 1;

			// If this isnt the first page, make a link to the previous one
			if ($current_page != 1) {

				echo '&nbsp; &nbsp; <a href="' . $script_name . '.php?id=' . $id . '&s=' . ($start - $display) . '&p=' . $pages . '">Previous  </a>';

			}

			// Numbered pages
			for ($i = 1; $i <= $pages; $i++)  {
				if ($i != $current_page)  {

					echo '&nbsp; &nbsp;<a href="' . $script_name . '.php?id=' . $id . '&s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '</a>&nbsp; &nbsp;';

				} else {

					echo $i . '   ';

				}
			}

			// If this isnt that last page, make a link to the next page
			if ($current_page != $pages) {

				echo '<a href="' . $script_name . '.php?id=' . $id . '&s=' . ($start + $display) . '&p=' . $pages . '">  Next</a>';
			}

			echo '</p>';
		}
}

?>
