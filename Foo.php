<?php

/**
 * Single line string / number output function.
 *
 * A more simple version of Foo. Does not display any code tracing, has more
 * basic styles.
 *
 * @return void
 */
if (!function_exists('fo')) {
	function fo($bar, $title = null) {
		if (is_string($bar) || is_numeric($bar) || is_bool($bar)) {
			echo "<p style='margin:0;padding:0;background-color:#fff;color:#000;font-family:courier;font-size:12px;'>";
			
			if ($title !== null) {
				echo '<strong>' . $title . ' :</strong> ';
			}

			if (is_bool($bar)) echo ($bar) ? 'true' : 'false'; else echo $bar;

			echo "</p>";
		}
		elseif(is_array($bar) || is_object($bar)) {
			if ($title !== null) {
				echo "<strong style='margin:0;padding:0;background-color:#fff;color:#000;font-family:courier;font-size:12px;'>
						$title :
					  </strong>";
			}
			echo "<pre style='margin:0;padding:0;background-color:#fff;color:#000;font-family:courier;font-size:12px;'>";
			print_r($bar);
			echo '</pre>';
		}
	}
}

/**
 * Output to the JS console
 *
 * This is a helper function, it just runs foo with options set for console output
 *
 * @param object $bar [optional]
 * @param object $title [optional]
 * @return void
 */
if (!function_exists('foc')) {
	function foc($bar) {
		foo($bar, null, null, true);
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

		// setup the vars to use in the main output

	    $file = $debug[0]['file'];
	    $line = $debug[0]['line'];

		// create a debug string in the style of debug_print_backtrace()

		$backtrace = '';

		foreach ($debug as $key => $arr) {
			
			$args = array();

			foreach ($arr['args'] as $arg) {

				switch (gettype($arg)) {
					case 'array':
						$args[] = 'Array';
						break;
					case 'object':
						$args[] = get_class($arg) . ' Object';
						break;
					case 'boolean':
						$args[] = ($arg) ? 'true' : 'false';
						break;
					default:
						$args[] = $arg;
				}
			}
			
			$args = implode(', ', $args);
			$function = (isset($arr['class'])) ? $arr['class'] . '->' .$arr['function'] : $arr['function'];

			// backtrace is broken if call_user_func() is part of the trace
			if (isset($arr['file'])) {
				$backtrace .= "#$key  $function($args) called at [{$arr['file']}:{$arr['line']}]\n";
			}
		}
		
		// setup title based on object type

		$title = (is_null($title)) ?  @get_class($bar) . ' ' . ucfirst(gettype($bar)) : $title . ' - ' . ucfirst(gettype($bar));

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

		// Output ------------------------------------------------------------------
		
		$id = rand();
		
		// JS console output

		if ($console) {
			?>
			<script type='text/javascript'>
			<!--
			if (typeof(console) !== 'undefined') {
				//console.log(".@json_encode($bar).",".@json_encode("$file [called line:$line][length:$meta]").");
				console.log(<?php echo @json_encode($bar) ?>);
			}
			-->
			</script>
			<?php
		}

		// HTML output

		else {
			?>
			<script type='text/javascript'>
			<!--
			function toggle(id) {
				
				var toggle = document.getElementById('toggle-'+id);
				var elm_status = toggle.firstChild.firstChild;
				var txt_status = toggle.firstChild.firstChild.firstChild;
				var code = document.getElementById('data-'+id);
				var open = '[+]', close = '[-]';

				elm_status.style.display = 'inline';
				txt_status.nodeValue = close;
				<?php if ($open): ?>
				txt_status.nodeValue = close
				<?php else: ?>
				txt_status.nodeValue = open;
				<?php endif; ?>
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

			function toggle_backtrace(id) {

				var toggle = document.getElementById('backtrace-'+id);
				var toggle_status = document.getElementById('backtrace-'+id+'-status');

				if (toggle.style.display == 'none') {
					toggle.style.display = 'block';
					toggle_status.innerHTML = '[-]';
				} else {
					toggle.style.display = 'none';
					toggle_status.innerHTML = '[+]';
				}
			}
			-->
			</script>
			<?php

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
			$p_style = '
				font-size: 13px;
				margin: 0;
				padding: 5px 0 8px;
				cursor: pointer;
				color: #000;
			';
			$pre_style = '
				margin: 0;
				color:#fff;
				background-color:#4e4e4e;
				padding:5px;
				border-left:solid 5px #d7d7d7;
				font-size:13px;
			';
			$backtrace_link_style = '
				cursor: pointer;
				font-size: 11px;
			';
			$backtrace_style = '
				margin: 0;
				padding: 0 0 10px 10px;
				display: none;
			';
			echo "<div id='$id' class='debug' style='$div_style'>";
		    echo "<div onclick='javascript:toggle($id)' id='toggle-$id' style='$title_style'><strong><span>$status</span> $title $meta</strong></div>
				  <div id='data-$id' class='data' style='$display'>
		          <p style='$p_style' onclick='javascript:toggle_backtrace($id)'>[$file:$line]<span id='backtrace-$id-status'>[+]</span></p>
				  <pre id='backtrace-$id' style='$backtrace_style'>$backtrace</pre>
		          <pre id='pre-$id' style='$pre_style'>";
		    print_r($bar);
		    echo '</pre>';
			echo '</div>';
			echo '</div>';
		}
	}
}
?>