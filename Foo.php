<?php

if (!function_exists('fo')) {
	function fo($bar, $name = null) {
		echo "$string <br />";
	}
}

/**
 * Output to the JS console
 *
 * @param object $bar [optional]
 * @param object $title [optional]
 * @return void
 */
if (!function_exists('foc')) {
	function foc($bar, $title = null) {
		foo($bar, $title, null, true);
	}
}

/**
 * Dump data to the screen
 *
 * Dump the contents of any string / object, etc. to the browser. This provides
 * a more human readable output than either var_dump() or print_r().
 *
 * @author Bala Clark
 *
 * @param mixed $bar The data
 * @param string[optional] $title custom title
 * @param boolean[optional] $open open/close the output div by default?
 * @param boolean[optional] $console Dssplay in a JavaScript console instead of rendering HTML?
 * @return void
 */
if (!function_exists('foo')) {
	function foo($bar = '', $title = null, $open = true, $console = false) {

		// backtrace ---------------------------------------------------------------

	    $debug = debug_backtrace();

	    $file = $debug[0]['file'];
	    $line = $debug[0]['line'];

		if (is_null($title)) $title = ucfirst(gettype($bar));

		switch (gettype($bar)) {
			case 'string':
				$meta = strlen($bar);
				break;
			case 'array':
				$meta = count($bar);
				break;
			case 'boolean':
				$bar = ($bar) ? 'true' : 'false';
			default:
				$meta = '';
				break;
		}

		// output javascript -------------------------------------------------------

		$id = rand();

		// JS console output
		if ($console) {
			echo "
			<script type='text/javascript'>
			<!--
			if (typeof(console) !== 'undefined') {
				//console.log(".@json_encode($bar).",".@json_encode("$file [called line:$line][length:$meta]").");
				console.log(".@json_encode($bar).");
			}
			-->
			</script>
			";
		}
		// HTML output
		else {

			echo "
			<script type='text/javascript'>
			<!--
			function toggle(id) {
				var toggle = document.getElementById('toggle-'+id);
				var elm_status = toggle.firstChild.firstChild;
				var txt_status = toggle.firstChild.firstChild.firstChild;
				var code = document.getElementById('data-'+id);

				var open = '[+]';
				var close = '[-]'

				elm_status.style.display = 'inline';
				txt_status.nodeValue = close;

			";
			if ($open) {
				echo "
				txt_status.nodeValue = close;
				";
			} else {
				echo "
				txt_status.nodeValue = open;
				";
			}
			echo "
				// show
				if (code.style.display === 'none') {
					code.style.display = 'block';
					txt_status.nodeValue = close;
				}
				// hide
				else {
					code.style.display = 'none';
					txt_status.nodeValue = open;
				}
			}
			-->
			</script>
			";

			$display = !$open ? "display:none;" : '';
			$status = $open ? "[-]" : "[+]";
			$meta = !empty($meta) ? "($meta)" : '';

			$div_style = '
				font-family:courier;
				font-size:12px;
				background-color:#fff;
				border:solid 1px #bbb;
				margin:5px;
				padding:5px;
				text-align:left;
				width: 95%;
			';
			$title_style = '
				margin:0;
				padding:0;
				font-size:12px;
				cursor:pointer;
			';
			$pre_style = "
				color:#fff;
				background-color:#4e4e4e;
				padding:5px;
				border-left:solid 5px #d7d7d7;
				font-size:13px;
			";

			echo "<div id='$id' class='debug' style='$div_style'>";
		    echo "<div onclick='javascript:toggle($id)' id='toggle-$id' style='$title_style'><strong><span>$status</span> $title $meta</strong></div>
				  <div id='data-$id' class='data' style='$display'>
		          <p style='font-size:13px'>$file, [line: $line]</p>
		          <pre id='pre-$id' style='$pre_style'>";
		    print_r($bar);
		    echo '</pre>';
			echo '</div>';
			echo '</div>';
		}
	}
}
?>