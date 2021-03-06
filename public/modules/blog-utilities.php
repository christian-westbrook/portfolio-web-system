<?php

	// ---------------------------------------------------------------------------
	// Function   : extractBlogFromXML()
	// Engineer   : Christian Westbrook
	// Parameters : $path - A string representing a relative path from the root
	//              directory to a blog XML file.
	// Abstract   : This function extracts the data from a blog XML file and
	//              stores it in an array.
	// ---------------------------------------------------------------------------
	function extractBlogFromXML($path) {

		// Open a file stream
		$handle = fopen($path, 'r');

		// Control variables
		$inBlog = false;
		$opening = false;
		$reading = false;
		$closing = false;
		$tagComparisonIndex = NULL;

		// Storage variables;
		$blog = array();
		$tag = '';
		$content = '';
		$match = '';

		// Iterate through characters in the file stream
		while(!feof($handle)) {
			$character = fgetc($handle);

			// If we are looking for an opening tag
			if($inBlog && !$opening && !$reading && !$closing) {
				if($character == '<') {
					$opening = true;
				}
				else {
					continue;
				}
			}

			// If we are currently opening a new tag
			else if($inBlog && $opening && !$reading && !$closing) {
				if($character == '>') {
					$opening = false;

					if($tag == '</blog>') {
						$tag = '';
						$inBlog = false;
					}
					else {
						$reading = true;
					}
				}
				else {
					$tag .= $character;
				}
			}


			// If we are currently reading content
			else if($inBlog && !$opening && $reading && !$closing) {
				if($character == '<') {
					$reading = false;
					$closing = true;
					$tagComparisonIndex = 0;
				}
				else {
					$content .= $character;
				}
			}

			// If we are currently closing a tag
			else if($inBlog && !$opening && !$reading && $closing) {
				if($character = '>') {

					// Do stuff with the tag and content
					$blog[$tag] = $content;

					$tag = '';
					$content = '';
					$tagComparisonIndex = NULL;
					$closing = false;
				}
				else if($character != substr($tag, $tagComparisonIndex, 1)) {
					echo "Error: Bad closing tag!";
					break;
				}
				else {
					$tagComparisonIndex++;
				}
			}

			// If we aren't in a inBlog yet
			else if(!$inBlog) {
				$match .= $character;
				if(preg_match('/<blog>/i', $match)) {
					$match = '';
					$inBlog = true;
				}
			}

			// If some illegal state is reached
			else {
				echo "Error: Illegal state reached during blog extraction!";
				echo $opening;
				echo $reading;
				echo $closing;
				break;
			}

		}

		fclose($handle);
		return $blog;
	}

	// ---------------------------------------------------------------------------
	// Function   : transformBlog()
	// Engineer   : Christian Westbrook
	// Parameters : $blog - An array holding an individual blog's data.
	// Abstract   :
	// ---------------------------------------------------------------------------
	function transformBlog($blog) {
		$transformation = '';

		$transformation .= '<div class="blog">';
		$transformation .= '<h1 class="title">' . $blog['title'] . '</h1>';
		$transformation .= '<div class="blog-metadata">';
		$transformation .= '<p class="author">' . $blog['author'] . '</p>';

		$timestamp = strtotime($blog['date'] . ' ' . $blog['time']);
		$formattedDateTime = date('M d, Y g:ia', $timestamp);
		$transformation .= '<p class="date">' . $formattedDateTime . '</p>';
		$transformation .= '</div>';

		$content = $blog['content'];

		$transformation .= '<p class="content">' . $content . '</p>	';
		$transformation .= '</div>';

		return $transformation;
	}
?>