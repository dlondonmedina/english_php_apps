		</div><!-- content -->
	</div><!-- inner -->
	<footer>
		<div id="footcontent">
			<a role="button" class="ToTop" href="#top" title="to content top">top</a>
			<div id="footerbox">
				<p class="footer"><em>RW learning something: &copy; <?php $copyYear = 2017; // Set your website start date
    $curYear = date('Y'); // Keeps the second year updated
    echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '');
    ?></em></p>
			</div>
		</div>
	</footer>
</body>
<?php echo isset($custom_footer) ? $custom_footer : ''; ?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>
